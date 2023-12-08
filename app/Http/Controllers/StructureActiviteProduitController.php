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
use App\Models\StructureActiviteDate;
use Illuminate\Http\RedirectResponse;

class StructureActiviteProduitController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure, StructureActivite $activite): RedirectResponse
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

        $activite = StructureActivite::findOrFail($activite->id);

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
                'structure_produit_id' => $structureProduit->id,
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

        //insertion ds le planning
        if($request->dates && $request->times) {
            $allDates = [];
            $combinedDatePairs = [];
            $start = Carbon::parse($request->dates[0])->startOfDay();
            $end = Carbon::parse($request->dates[1])->startOfDay();

            // Create an array of all dates between the start and end dates
            while ($start->lte($end)) {
                $allDates[] = $start->copy();
                $start->addDay();
            }

            $startTime = $request->times[0];
            $endTime = $request->times[1];

            foreach ($allDates as $date) {
                $startDateTime = $date->copy()->setTime($startTime['hours'], $startTime['minutes']);
                $endDateTime = $date->copy()->setTime($endTime['hours'], $endTime['minutes']);

                $combinedDatePairs[] = [
                    'start' => $startDateTime->toDateTimeString(),
                    'end' => $endDateTime->toDateTimeString()
                ];
            }

            foreach($combinedDatePairs as $combinedDatePair) {
                StructurePlanning::create([
                    'structure_id' => $request->structure_id,
                    'discipline_id' => $activite->discipline_id,
                    'categorie_id' => $activite->categorie_id,
                    'activite_id' => $activite->id,
                    'produit_id' => $structureProduit->id,
                    'title' => $activite->titre,
                    'start' => $combinedDatePair['start'] ?? "",
                    'end' => $combinedDatePair['end'] ?? "",
                ]);
            }
        }


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
    public function update(Request $request, Structure $structure, StructureActivite $activite, StructureProduit $produit): RedirectResponse
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
        $activite = StructureActivite::findOrFail($activite->id);
        $structureProduit = StructureProduit::findOrFail($produit->id);

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

        $activiteProdDate =
            StructureActiviteDate::where('structure_activite_id', $activite->id)->where('structure_produit_id', $structureProduit->id)->first();

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
                'structure_produit_id' => $structureProduit->id,
                'dayopen' => $dayopen ?? $activiteProdDate->dayopen,
                'dayclose' => $dayclose ?? $activiteProdDate->dayclose,
                'houropen' => $houropen ?? $activiteProdDate->houropen,
                'hourclose' => $hourclose ?? $activiteProdDate->hourclose,
                'date_debut' => $date_seule ?? $activiteProdDate->date_debut,
                'time_debut' => $time_seule ?? $activiteProdDate->time_debut,
                'start_month' => $startMonth ?? $activiteProdDate->start_month,
                'end_month' => $endMonth ?? $activiteProdDate->end_month,
            ];
            if($activiteProdDate !== null) {
                $activiteProdDate->update($data);
            } else {
                $activite->dates()->create($data);
            }

        }

        //insertion ds le planning
        if($request->dates && $request->times) {
            $allDates = [];
            $combinedDatePairs = [];
            $start = Carbon::parse($request->dates[0])->startOfDay();
            $end = Carbon::parse($request->dates[1])->startOfDay();

            // Create an array of all dates between the start and end dates
            while ($start->lte($end)) {
                $allDates[] = $start->copy();
                $start->addDay();
            }

            $startTime = $request->times[0];
            $endTime = $request->times[1];

            foreach ($allDates as $date) {
                $startDateTime = $date->copy()->setTime($startTime['hours'], $startTime['minutes']);
                $endDateTime = $date->copy()->setTime($endTime['hours'], $endTime['minutes']);

                $combinedDatePairs[] = [
                    'start' => $startDateTime->toDateTimeString(),
                    'end' => $endDateTime->toDateTimeString()
                ];
            }

            $previousDates = StructurePlanning::where('structure_id', $structure->id)->where('discipline_id', $activite->discipline_id)
            ->where('categorie_id', $activite->categorie_id)
            ->where('activite_id', $activite->id)
            ->where('produit_id', $structureProduit->id)
            ->where('title', $activite->titre)
            ->get();
            if(isset($previousDates)) {
                foreach($previousDates as $exDate) {
                    $exDate->delete();
                }
            }

            foreach($combinedDatePairs as $combinedDatePair) {
                StructurePlanning::create([
                    'structure_id' => $structure->id,
                    'discipline_id' => $activite->discipline_id,
                    'categorie_id' => $activite->categorie_id,
                    'activite_id' => $activite->id,
                    'produit_id' => $structureProduit->id,
                    'title' => $activite->titre,
                    'start' => $combinedDatePair['start'] ?? "",
                    'end' => $combinedDatePair['end'] ?? "",
                ]);
            }
        }

        $actifValue = $request->actif ? true : false;

        $structureProduit->update([
                "actif" => $actifValue,
                'lieu_id' => $request->adresse ?? $structureProduit->lieu_id,
        ]);

        $criteresValuesSets = $request->criteres;

        if (isset($criteresValuesSets)) {

            $oldCriteresValues = StructureProduitCritere::with('sous_criteres')->where('produit_id', $structureProduit->id)->get();
            foreach ($oldCriteresValues as $oldCriteresValue) {
                $oldCriteresValue->delete();
            }

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
    public function destroy(Structure $structure, StructureActivite $activite, StructureProduit $produit): RedirectResponse
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

    public function duplicate(Structure $structure, StructureActivite $activite, StructureProduit $produit): RedirectResponse
    {
        $activite = StructureActivite::with([
                    'structure',
                    'categorie',
                    'discipline'
                ])->find($activite->id);

        $originalProduit = StructureProduit::withRelations()->findOrFail($produit->id);

        $newProduit = $originalProduit->replicate();
        $newProduit->save();

        foreach ($originalProduit->criteres as $critere) {
            $newRelated = $critere->replicate();
            $newRelated->structure_id = $newProduit->structure_id;
            $newRelated->discipline_id = $newProduit->discipline_id;
            $newRelated->categorie_id = $newProduit->categorie_id;
            $newCrit = $newProduit->criteres()->save($newRelated);

            foreach ($newCrit->sous_criteres as $sous_critere) {
                $newSousCrit = $sous_critere->replicate();
                $newCrit->sous_criteres()->save($newSousCrit);
            }
        }
        foreach ($originalProduit->plannings as $planning) {
            $newRelated = $planning->replicate();
            $newProduit->plannings()->save($newRelated);
        }
        foreach ($originalProduit->tarifs as $tarif) {
            $newProduit->tarifs()->attach($tarif);
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
        $prodCrit = StructureProduitCritere::where('produit_id', $structureProduit->id)->where('critere_id', $critereId)->where('valeur_id', $critereValeurId)->first();

        if(isset($prodCrit)) {
            StructureProduitSousCritere::create([
                'activite_id' => $activite->id,
                'produit_id' => $structureProduit->id,
                'critere_id' => $critereId,
                'prod_crit_id' => $prodCrit->id,
                'critere_valeur_id' => $critereValeurId,
                'sous_critere_id' => $sousCritereId,
                'sous_critere_valeur_id' => $souscriteresValues['id'] ?? null,
                'valeur' => $souscriteresValues['valeur'] ?? $souscriteresValues,
            ]);
        }

    }
}
