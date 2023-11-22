<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Structure;
use App\Rules\NestedArrays;
use Illuminate\Http\Request;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureTarifTypeInfo;
use App\Models\StructureProduitCritere;
use App\Models\LiensDisCatCritValSsCrit;
use Illuminate\Support\Facades\Redirect;
use App\Models\StructureProduitSousCritere;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class StructureActiviteProduitController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure, StructureActivite $activite)
    {
        $request->validate([
            'criteres' => ['nullable'],
            'souscriteres' => ['nullable'],
            'adresse' => ['nullable', Rule::exists(StructureAddress::class, 'id')],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'time_seule' => ['nullable', 'array'],
            'times' => ['nullable', new NestedArrays()],
            'date_seule' => ['nullable', 'date'],
            'dates' => ['nullable', 'array'],
            'months' => ['nullable', new NestedArrays()],
            'instructeur_email' => ['nullable', 'email:filter', 'exists:users,email'],
            'instructeur_contact' => ['nullable'],
            'instructeur_phone' => ['nullable'],
            'rayon_km' => ['nullable'],
        ]);

        $activite = StructureActivite::with([
                    'structure',
                    'categorie',
                    'discipline'
                ])->where('structure_id', $structure->id)
                    ->findOrFail($activite->id);


        // Dates et horaires
        if($request->time_seule) {
            $time_seule = Carbon::createFromTime($request->time_seule['hours'], $request->time_seule['minutes'])->format('H:i');
        }

        if($request->times) {
            $times = $request->times;
            $horaires = array_map(function ($time) {
                return Carbon::createFromTime($time['hours'], $time['minutes'])->format('H:i');
            }, $times);
            $houropen = $horaires[0];
            $hourclose = $horaires[1];
        }

        if($request->date_seule) {
            $date_seule = Carbon::parse($request->date_seule)->format('Y-m-d');
        }

        if($request->dates) {
            $dayopen = Carbon::parse($request->dates[0])->format('Y-m-d');
            $dayclose = Carbon::parse($request->dates[1])->format('Y-m-d');
        }

        if ($request->months) {
            $startMonth = Carbon::create(
                $request->months[0]['year'],
                $request->months[0]['month'] + 1,
                1
            )->startOfMonth();

            $endMonth = Carbon::create(
                $request->months[1]['year'],
                $request->months[1]['month'] + 1,
                1
            )->endOfMonth();
        }

        if (
            (isset($time_seule) && !empty($time_seule)) ||
            (isset($houropen) && !empty($houropen)) ||
            (isset($hourclose) && !empty($hourclose)) ||
            (isset($date_seule) && !empty($date_seule)) ||
            (isset($dayopen) && !empty($dayopen)) ||
            (isset($dayclose) && !empty($dayclose)) ||
            (isset($startMonth) && !empty($startMonth)) ||
            (isset($endMonth) && !empty($endMonth))
        ) {
            $data = [
                'structure_activite_id' => $activite->id,
                'dayopen' => $dayopen ?? null,
                'dayclose' => $dayclose ?? null,
                'houropen' => $houropen ?? null,
                'hourclose' => $hourclose ?? null,
                'date_debut' => $date_seule ?? null,
                'time_debut' => $time_seule ?? null,
                'start_month' => $startMonth ?? null,
                'end_month' => $endMonth ?? null,
            ];

            $activiteDatesTimes = $activite->dates()->create($data);
        }


        if($request->address) {
            //check if address exist
            foreach($structure->adresses as $address) {
                if (($address->address_lat === $request->address_lat) && ($address->address_lng === $request->address_lng)) {
                    return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug])->with('error', 'Cette adresse existe déjà dans votre liste d\'adresses');
                }
            }
            $city = City::where('code_postal', $request->zip_code)->firstOrFail();
            $cityId = $city->id;

            $validatedAddress = [
                'structure_id' => $structure->id,
                'name' => $structure->name,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'city' => $request->city,
                'country' => $request->country,
                'city_id' => $cityId,
                'country_id' => $structure->country_id,
                'address_lat' => $request->address_lat,
                'address_lng' => $request->address_lng,
                'phone' => $structure->phone1,
                'email' => $structure->email,
            ];

            $newStructureAddress = StructureAddress::create($validatedAddress);
            $structureAddressId = $newStructureAddress->id;

        } elseif ($request->adresse) {
            $structureAddressId = $request->adresse;
        } else {
            $structureAddressId = $structure->adresses->first()->id;
        }

        $structureProduit = StructureProduit::create([
            'structure_id' => $structure->id,
            'discipline_id' => $activite->discipline_id,
            'categorie_id' => $activite->categorie_id,
            'activite_id' => $activite->id,
            "actif" => true,
            'lieu_id' => $structureAddressId,
            'horaire_id' => null,
            'reservable' => 0,
        ]);

        $criteresValuesSets = $request->criteres;

        if (isset($criteresValuesSets)) {
            foreach ($criteresValuesSets as $critereId => $criteresValues) {
                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $critereId)->first();

                $this->insertCriteresRecursively($structure, $activite, $structureProduit, $critereId, $criteresValues, $defaut);
            }
        }

        $souscriteresValues = $request->souscriteres;
        if(isset($souscriteresValues)) {
            foreach ($souscriteresValues as $souscritereId => $souscritereValue) {

                $sousCritere = LiensDisCatCritValSsCrit::with([
                    'critere_valeur',
                    'sous_criteres_valeurs'
                ])->find($souscritereId);

                $critereId = $sousCritere->critere_valeur->discipline_categorie_critere_id;
                $critereValeurId = $sousCritere->critere_valeur->id;
                $sousCritereId = $sousCritere->id;

                $this->insertSousCriteresRecursively($activite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
            }
        }

        if($request->instructeur_email) {
            $user = User::where('email', $request->instructeur_email)->firstOrFail();
            $instructeur = $activite->instructeurs()->attach($user, [
                'contact' => $request->instructeur_contact,
                'email' => $request->instructeur_email,
                'phone' => $request->instructeur_phone,
            ]);
        }

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug, 'categorie' => $activite->categorie->id ])->with('success', 'Produit ajouté.');

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {

        $request->validate([
            'criteres' => ['nullable'],
            'souscriteres' => ['nullable'],
            'adresse' => ['nullable', Rule::exists(StructureAddress::class, 'id')],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'time_seule' => ['nullable', 'array'],
            'times' => ['nullable', new NestedArrays()],
            'date_seule' => ['nullable', 'date'],
            'dates' => ['nullable', 'array'],
            'months' => ['nullable', new NestedArrays()],
            'instructeur_email' => ['nullable', 'email:filter', 'exists:users,email'],
            'instructeur_contact' => ['nullable'],
            'instructeur_phone' => ['nullable'],
            'rayon_km' => ['nullable'],
        ]);


        $activite = StructureActivite::with([
                    'structure',
                    'categorie',
                    'discipline'
                ])
                    ->findOrFail($activite->id);

        $structureProduit = StructureProduit::findOrFail($produit->id);

        dd($request->all(), $produit);

        if(isset($request->date) || isset($request->time)) {

            $dayopen = Carbon::parse($request->date[0])->format('Y-m-d');
            $dayclose = Carbon::parse($request->date[1])->format('Y-m-d');

            $heureopen = $request->time[0]['hours'];
            $minuteopen = $request->time[0]['minutes'];
            // Construct the time string in HH:ii:ss format
            $houropen = sprintf('%02d:%02d', $heureopen, $minuteopen);

            $heureclose = $request->time[1]['hours'];
            $minuteclose = $request->time[1]['minutes'];
            // Construct the time string in HH:ii:ss format
            $hourclose = sprintf('%02d:%02d', $heureclose, $minuteclose);

            $dayTime = StructureHoraire::firstOrCreate([
                'structure_id' => $structure->id,
                'dayopen' => $dayopen,
                'dayclose' => $dayclose,
                'houropen' => $houropen,
                'hourclose' => $hourclose,
            ]);
        }

        $actifValue = $request->actif ? "1" : "0";

        $structureProduit->update([
                "actif" => $actifValue,
                'lieu_id' => $request->adresse ?? $structureProduit->lieu_id,
                'horaire_id' => $dayTime->id ?? $structureProduit->horaire_id,
        ]);

        $criteresValues = $request->criteres;

        if(isset($criteresValues)) {
            StructureProduitCritere::where('structure_id', $structure->id)
            ->where('discipline_id', $structureProduit->discipline_id)
            ->where('categorie_id', $structureProduit->categorie_id)
            ->where('activite_id', $structureProduit->activite_id)
            ->where('produit_id', $structureProduit->id)
            ->delete();

            foreach ($criteresValues as $key => $critereValue) {
                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $key)->first();

                if (isset($critereValue)) {
                    if (is_array($critereValue)) {
                        foreach ($critereValue as $value) {
                            $structureProduitCriteres = StructureProduitCritere::create([
                                'structure_id' => $structure->id,
                                'discipline_id' => $structureProduit->discipline_id,
                                'categorie_id' => $structureProduit->categorie_id,
                                'activite_id' => $structureProduit->activite_id,
                                'produit_id' => $structureProduit->id,
                                'critere_id' => $key,
                                'valeur' => $value,
                            ]);
                        }
                    } else {
                        $structureProduitCriteres = StructureProduitCritere::create([
                            'structure_id' => $structure->id,
                            'discipline_id' => $structureProduit->discipline_id,
                            'categorie_id' => $structureProduit->categorie_id,
                            'activite_id' => $structureProduit->activite_id,
                            'produit_id' => $structureProduit->id,
                            'critere_id' => $key,
                            'valeur' => $critereValue ?? $defaut->valeur,
                        ]);
                    }
                }
            }
        }


        // newAdresse
        if($request->address) {

            $city = City::where('code_postal', $request->zip_code)->firstOrFail();
            $cityId = $city->id;

            $validatedAddress = [
                'structure_id' => $structure->id,
                'name' => $structure->name,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'city' => $request->city,
                'country' => $request->country,
                'city_id' => $cityId,
                'country_id' => $structure->country_id,
                'address_lat' => $request->address_lat,
                'address_lng' => $request->address_lng,
                'phone' => $structure->phone1,
                'email' => $structure->email,
            ];

            $structureAddress = StructureAddress::create($validatedAddress);

            $structureProduit->update(['lieu_id' => $structureAddress->id]);

        }

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug, 'categorie' => $activite->categorie->id])->with('success', 'Produit mise à jour.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        $activite = StructureActivite::with([
            'structure',
            'categorie',
            'discipline'
        ])->find($activite->id);

        $produit = StructureProduit::findOrFail($produit->id);

        $produitCriteres = StructureProduitCritere::where('produit_id', $produit->id)->get();

        $plannings = StructurePlanning::where('produit_id', $produit->id)->get();

        if($plannings->isNotEmpty()) {
            foreach($plannings as $planning) {
                $planning->delete();
            }
        }

        if(isset($produitsCriteres)) {
            foreach($produitCriteres as $critere) {
                $critere->delete();
            }
        }

        $produit->tarifs()->detach();

        $produit->delete();

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug, 'categorie' => $activite->categorie->id])->with('success', "Le produit a bien été supprimé");
    }

    public function duplicate(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        $activite = StructureActivite::with([
                    'structure',
                    'categorie',
                    'discipline'
                ])->find($activite->id);

        $originalProduit = StructureProduit::with('criteres')->findOrFail($produit->id);

        $originalProduitCriteres = StructureProduitCritere::where('produit_id', $originalProduit->id)->get();

        $newProduit = new StructureProduit();
        $newProduit->structure_id = $originalProduit->structure_id;
        $newProduit->discipline_id = $originalProduit->discipline_id;
        $newProduit->categorie_id = $originalProduit->categorie_id;
        $newProduit->activite_id = $originalProduit->activite_id;
        $newProduit->lieu_id = $originalProduit->lieu_id;
        $newProduit->actif = $originalProduit->actif;
        $newProduit->horaire_id = $originalProduit->horaire_id;
        // $newProduit->tarif_id = $originalProduit->tarif_id;
        $newProduit->reservable = $originalProduit->reservable;
        $newProduit->id = null;
        $newProduit->save();

        if($originalProduit->tarifs->isNotEmpty()) {
            foreach($originalProduit->tarifs as $tarif) {
                $newProduit->tarifs()->attach($tarif->id);
            }
        }

        foreach($originalProduitCriteres as $produitCritere) {
            $newProduitCritere = new StructureProduitCritere();
            $newProduitCritere->structure_id = $produitCritere->structure_id;
            $newProduitCritere->discipline_id = $produitCritere->discipline_id;
            $newProduitCritere->categorie_id = $produitCritere->categorie_id;
            $newProduitCritere->activite_id = $produitCritere->activite_id;
            $newProduitCritere->produit_id = $newProduit->id;
            $newProduitCritere->critere_id = $produitCritere->critere_id;
            $newProduitCritere->valeur = $produitCritere->valeur;
            $newProduitCritere->defaut = $produitCritere->defaut;
            $newProduitCritere->id = null;
            $newProduitCritere->save();
        }

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug, 'categorie' => $activite->categorie->id])->with('success', "Le produit a bien été dupliqué");
    }

    private function insertCriteresRecursively($structure, $activite, $structureProduit, $critereId, $criteresValues, $defaut)
    {
        if (isset($criteresValues['valeur'])) {
            $critereValueId = $criteresValues['id'];
            $critereValue = $criteresValues['valeur'];
            $valeurId = isset($critereValueId) ? $critereValueId : ($defaut ? $defaut->id : null);
            $valeur = isset($critereValue) ? $critereValue : ($defaut ? $defaut->valeur : null);

            $this->createStructureProduitCritere($structure, $activite, $structureProduit, $critereId, $valeurId, $valeur);
        } else {
            foreach ($criteresValues as $critereValue) {
                $this->insertCriteresRecursively($structure, $activite, $structureProduit, $critereId, $critereValue, $defaut);
            }
        }
    }

    private function createStructureProduitCritere($structure, $activite, $structureProduit, $critereId, $valeurId = null, $valeur = null)
    {
        StructureProduitCritere::create([
            'structure_id' => $structure->id,
            'discipline_id' => $activite->discipline_id,
            'categorie_id' => $activite->categorie_id,
            'activite_id' => $activite->id,
            'produit_id' => $structureProduit->id,
            'critere_id' => $critereId,
            'valeur_id' => $valeurId,
            'valeur' => $valeur ?? "",
        ]);
    }

    private function insertSousCriteresRecursively($activite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue)
    {
        if (isset($souscritereValue['id'])) {
            $this->createProduitSousCritere($activite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
        } elseif(is_array($souscritereValue)) {
            foreach ($souscritereValue as $subSouscriteresValue) {
                $this->insertSousCriteresRecursively($activite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $subSouscriteresValue);
            }
        } else {
            $this->createProduitSousCritere($activite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
        }
    }

    private function createProduitSousCritere($activite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscriteresValues)
    {
        StructureProduitSousCritere::create([
            'activite_id' => $activite->id,
            'produit_id' => $structureProduit->id,
            'critere_id' => $critereId,
            'critere_valeur_id' => $critereValeurId,
            'sous_critere_id' => $sousCritereId,
            'sous_critere_valeur_id' => $souscriteresValues['id'] ?? null,
            'valeur' => $souscriteresValues['valeur'] ?? $souscriteresValues,
        ]);
    }
}
