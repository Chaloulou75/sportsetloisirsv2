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
use Illuminate\Support\Collection;
use App\Models\LienDisCatTariftype;
use Illuminate\Support\Facades\Cache;
use App\Models\LienDisCatTarBookingField;
use App\Models\LienDisCatTarBookingFieldSousField;
use Illuminate\Http\RedirectResponse;

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
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $sessionId = session()->getId();
        $user = auth()->user();

        if ($user || $sessionId) {
            $query = ProductReservation::withRelations()->withCount('plannings');
            if ($user && $sessionId) {
                $query->where('user_id', $user->id)->orWhere('session_id', $sessionId);
            } elseif ($sessionId) {
                $query->where('session_id', $sessionId);
            } elseif ($user) {
                $query->where('user_id', $user->id);
            }
            $reservations = $query->get();
        }

        return Inertia::render('Panier/Index', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'reservations' => $reservations ?? null
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'produitId' => ['required', Rule::exists(StructureProduit::class, 'id')],
            'catTarifId' => ['nullable', Rule::exists(LienDisCatTariftype::class, 'id')],
            'attributs' => ['nullable', 'array'],
            'sousattributs' => ['nullable', 'array'],
            'plannings' => ['nullable', 'array'],
        ]);
        // $request->all();
        $user = auth()->user();
        $produit = StructureProduit::withRelations()->find($request->produitId);
        if($request->catTarifId) {
            $catTarif = StructureCatTarif::find($request->catTarifId);
        }

        $activiteId = $produit->activite->id;
        $activite = StructureActivite::find($activiteId);
        if($request->plannings) {
            $creneaux = StructurePlanning::whereIn('id', $request->plannings)->get();
        }
        if($produit->criteres) {
            $produitCriteres = $produit->criteres()->pluck('valeur')->toJson();
        }

        $sessionId = session()->getId();
        $sessionPanierProducts = session()->get('panierProducts', []);

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
            'user_payeur_id' => $user->id ?? null,
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

        $sessionPanierProducts[] = [
            'session_id' => $sessionId ?? null,
            'ip_address' => $request->ip() ?? null,
            'user_id' => $user->id ?? null,
            'produit_id' => $produit->id,
            'cat_tarif_id' => $catTarif->id ?? null,
            'planning_ids' => $creneaux->pluck('id')->toArray() ?? null,
            'reservation_id' => $newReservation->id ?? null,
        ];
        session()->put('panierProducts', $sessionPanierProducts);

        return to_route('structures.activites.show', ['activite' => $activite])->with('success', 'Produit ajouté à votre panier');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
        session()->put('panierProducts', $sessionPanierProducts);

        $reservation->delete();

        return to_route('panier.index')->with('success', 'Produit supprimé de votre panier');

    }

    private function getSessionReservations(): Collection
    {
        $sessionReservations = collect();
        $sessionProducts = session()->get('panierProducts', []);

        foreach ($sessionProducts as $panierProduct) {
            $sessionReservations->push([
                'session_id' => $panierProduct['session_id'],
                'user_id' => $panierProduct['user_id'] ?? null,
                'produit_id' => $panierProduct['produit_id'],
                'cat_tarif_id' => $panierProduct['cat_tarif_id'],
                'planning_ids' => $panierProduct['planning_ids'],
                'ip_address' => $panierProduct['ip_address'],
                'reservation_id' => $panierProduct['reservation_id'],
            ]);
        }

        return $sessionReservations;
    }
}
