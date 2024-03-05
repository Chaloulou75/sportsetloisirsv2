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

        $sessionReservations = $this->getSessionReservations();

        if(auth()->user()) {
            $reservations = auth()->user()->productReservations()->withRelations()->get();
        }

        $produitsDesired = StructureProduit::withRelations()
        ->whereIn('structures_produits.id', $sessionReservations->pluck('produit_id'))
        ->get();

        return Inertia::render('Panier/Index', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'produitsDesired' => $produitsDesired,
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
    public function store(Request $request)
    {
        request()->validate([
            'produitId' => ['required', Rule::exists(StructureProduit::class, 'id')],
            'catTarifId' => ['nullable', Rule::exists(LienDisCatTariftype::class, 'id')],
            'attributs' => ['nullable', 'array'],
            'sousattributs' => ['nullable', 'array'],
            'plannings' => ['nullable', 'array'],
        ]);

        $user = auth()->user();
        $produit = StructureProduit::withRelations()->find($request->produitId);
        $catTarif = StructureCatTarif::find($request->catTarifId);
        $activiteId = $produit->activite->id;
        $activite = StructureActivite::find($activiteId);
        $creneaux = StructurePlanning::whereIn('id', $request->plannings)->get();
        $produitCriteres = $produit->criteres()->pluck('valeur')->toJson();
        //ajout produit au panier
        $sessionId = session()->getId();
        $sessionPanierProducts = session()->get('panierProducts', []);
        $sessionPanierProducts[] = [
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'user_id' => $user->id ?? null,
            'produit_id' => $produit->id,
            'cat_tarif_id' => $catTarif->id ?? null,
            'planning_ids' => $creneaux->pluck('id')->toArray()
        ];
        session()->put('panierProducts', $sessionPanierProducts);

        $newReservation = ProductReservation::create([
            'session_id' => $sessionId,
            'user_id' => $user->id ?? null,
            'discipline_id' => $produit->discipline_id,
            'categorie_id' => $produit->categorie_id,
            'structure_id' => $produit->structure_id,
            'activite_id' => $activiteId,
            'activite_title' => $activite->titre,
            'produit_id' => $produit->id,
            'produit_criteres' => $produitCriteres,
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

                $bookingField = LienDisCatTarBookingField::with(['valeurs', 'sous_fields', 'sous_fields.valeurs'])->find($key);

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
                        //SOMETHING WRONG HERE
                        if($bookingSousField->sousfield_id === $k) {
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
                                    'booking_field_id' => $k,
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
                $newReservation->plannings()->attach($creneau);
            }
        }

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
    public function destroy(string $id)
    {
        //
    }

    private function getSessionReservations(): Collection
    {
        $sessionReservations = collect();
        $sessionProducts = session()->get('panierProducts', []);
        dd($sessionProducts);
        foreach ($sessionProducts as $panierProduct) {

            $sessionReservations->push([
                'session_id' => $panierProduct['session_id'],
                'user_id' => null,
                'produit_id' => $panierProduct['produit_id'],
                'cat_tarif_id' => $panierProduct['cat_tarif_id'],
            ]);
        }

        return $sessionReservations;
    }
}
