<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureCatTarif;
use App\Models\StructurePlanning;
use App\Models\ProductReservation;
use App\Http\Resources\CityResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisCatTarBookingFieldSousField;

class PanierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $user = auth()->user();
        $panierProductsInSession = session()->get('panierProducts');
        $panierProdCount = count($panierProductsInSession ?? []);

        if($panierProdCount > 0 && $user) {
            $reservationIds = array_column($panierProductsInSession, 'reservation_id');
            $reservations = ProductReservation::withRelations()->whereIn('id', $reservationIds)->orWhere('user_id', $user->id)->where('paid', false)->get();
        } elseif($panierProdCount > 0) {
            $reservationIds = array_column($panierProductsInSession, 'reservation_id');
            $reservations = ProductReservation::withRelations()->whereIn('id', $reservationIds)->where('paid', false)->get();
        } elseif($user) {
            $reservations = ProductReservation::withRelations()->where('user_id', $user->id)->where('paid', false)->get();
        }

        return Inertia::render('Panier/Index', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'reservations' => fn () => $reservations ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'produitId' => ['required', Rule::exists(StructureProduit::class, 'id')],
            'catTarifId' => ['nullable', Rule::exists(StructureCatTarif::class, 'id')],
            'attributs' => ['nullable'],
            'sousattributs' => ['nullable'],
            'plannings' => ['nullable'],
        ]);

        $user = auth()->user();
        $produit = StructureProduit::withRelations()->find($request->produitId);
        if($request->catTarifId) {
            $catTarif = StructureCatTarif::find($request->catTarifId);
        }

        $activiteId = $produit->activite->id;
        $activite = StructureActivite::find($activiteId);

        $creneaux = null;
        if($request->plannings) {
            $creneaux = StructurePlanning::whereIn('id', $request->plannings)->get();
        }
        if($produit->criteres) {
            $produitCriteres = $produit->criteres()->pluck('valeur')->toJson();
        }

        $sessionId = $request->session()->getId();

        $newReservation = ProductReservation::create([
            'session_id' => $sessionId,
            'user_id' => $user->id ?? null,
            'discipline_id' => $produit->discipline_id,
            'categorie_id' => $produit->categorie_id,
            'structure_id' => $produit->structure_id,
            'activite_id' => $activiteId,
            'activite_title' => $activite->titre,
            'produit_id' => $produit->id,
            'produit_criteres' => $produitCriteres ?? null,
            'cat_tarif_id' => $catTarif->id ?? null,
            'tarif_title' => $catTarif->titre ?? null,
            'tarif_amount' => $catTarif->amount ?? null,
            'quantity' => 1,
            'paid' => false,
            'stripe_session_id' => null,
            'customer_id' => null,
            'paiement_datetime' => null,
            'paiement_method' => null,
            'transaction_number' => null,
            'client_confirmed' => false,
            'datetime_client_confirmed' => null,
            'client_cancelled' => null,
            'datetime_client_cancelled' => null,
            'pending' => false,
            'confirmed' => false,
            'datetime_structure_confirmed' => null,
            'finished' => false,
            'datetime_structure_finished' => null,
            'cancelled' => false,
            'datetime_structure_cancelled' => null,
            'code' => null,
            'code_confirmed' => null,
            'datetime_code_confirmed' => null
        ]);

        if($request->attributs) {
            foreach ($request->attributs as $key => $attribut) {

                // $bookingField = LienDisCatTarBookingField::with(['valeurs', 'sous_fields', 'sous_fields.valeurs'])->find($key);

                if (is_array($attribut) && isset($attribut[0]) && is_array($attribut[0])) {
                    foreach ($attribut as $attr) {
                        $reservationAttribut = $newReservation->attributs()->create([
                              'booking_field_id' => $attr['cat_tar_field_id'] ?? $key,
                              'booking_field_valeur_id' => $attr['id'],
                              'valeur' => $attr['valeur']
                          ]);
                    }
                } elseif (is_array($attribut)) {
                    $reservationAttribut = $newReservation->attributs()->create([
                         'booking_field_id' => $attribut['cat_tar_field_id'] ?? $key,
                         'booking_field_valeur_id' => $attribut['id'],
                         'valeur' => $attribut['valeur']
                     ]);
                } elseif (is_string($attribut) || is_numeric($attribut)) {
                    $reservationAttribut = $newReservation->attributs()->create([
                         'booking_field_id' => $key,
                         'valeur' => $attribut
                     ]);
                }

                if($request->sousattributs) {
                    foreach ($request->sousattributs as $k => $sousattribut) {
                        $bookingSousField = LienDisCatTarBookingFieldSousField::with(['booking_field','valeurs'])->find($k);

                        if($bookingSousField->booking_field->id === $reservationAttribut->booking_field_id) {

                            if (is_array($sousattribut) && isset($sousattribut[0]) && is_array($sousattribut[0])) {
                                foreach ($sousattribut as $subAttribut) {
                                    $reservationSsAttribut = $reservationAttribut->reservation_sous_attributs()->create([
                                        'reservation_id' => $newReservation->id,
                                        'booking_field_ss_field_id' => $subAttribut['sousfield_id'] ?? $k,
                                        'booking_ss_field_valeur_id' => $subAttribut['id'],
                                        'valeur' => $subAttribut['valeur']
                                    ]);
                                }
                            } elseif (is_array($sousattribut)) {
                                $reservationSsAttribut =  $reservationAttribut->reservation_sous_attributs()->create([
                                    'reservation_id' => $newReservation->id,
                                    'booking_field_ss_field_id' => $sousattribut['sousfield_id'] ?? $k,
                                    'booking_ss_field_valeur_id' => $sousattribut['id'],
                                    'valeur' => $sousattribut['valeur']
                                ]);

                            } elseif (is_string($sousattribut) || is_numeric($sousattribut)) {
                                $reservationSsAttribut = $reservationAttribut->reservation_sous_attributs()->create([
                                    'reservation_id' => $newReservation->id,
                                    'booking_field_ss_field_id' => $k,
                                    'valeur' => $sousattribut
                                ]);
                            }
                        }
                    }
                }
            }
        }
        if($request->plannings) {
            foreach($request->plannings as $creneau) {
                $newReservation->plannings()->attach($creneau, ['quantity' => 1]);
            }
        }

        $sessionPanierProducts = [
            'session_id' => $sessionId ?? null,
            'ip_address' => $request->ip() ?? null,
            'user_id' => $user->id ?? null,
            'produit_id' => $produit->id,
            'cat_tarif_id' => $catTarif->id ?? null,
            'planning_ids' => $creneaux ? $creneaux->pluck('id')->toArray() : null,
            'reservation_id' => $newReservation->id ?? null,
        ];
        $request->session()->push('panierProducts', $sessionPanierProducts);

        return to_route('structures.activites.show', ['activite' => $activite, 'slug' => $activite->slug_title ])->with('success', 'Produit ajouté à votre panier');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReservation $reservation): RedirectResponse
    {
        $sessionPanierProducts = session()->get('panierProducts', []);
        $sessionPanierProducts = array_filter($sessionPanierProducts, function ($item) use ($reservation) {
            return $item['reservation_id'] !== $reservation->id;
        });
        if($sessionPanierProducts) {
            session()->put('panierProducts', $sessionPanierProducts);
        }

        $reservation->delete();

        return to_route('panier.index')->with('success', 'Produit supprimé de votre panier');

    }

}
