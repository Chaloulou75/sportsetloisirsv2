<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureProduitCritere;
use App\Models\LiensDisCatCritValSsCrit;
use App\Models\StructureProduitSousCritere;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;
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
            'reservable' => true,
        ]);

        $criteresValuesSets = $request->criteres;

        if (isset($criteresValuesSets)) {
            foreach ($criteresValuesSets as $critereId => $criteresValues) {
                $critere = LienDisciplineCategorieCritere::withValeurs()->findOrFail($critereId);

                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', true)->where('discipline_categorie_critere_id', $critereId)->first();

                $this->insertCriteresRecursively($structure, $activite, $structureProduit, $critere, $criteresValues, $defaut);
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

                $this->insertSousCriteresRecursively($activite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscritereValue);

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

        $datesProduit = StructureProduitCritere::withWhereHas('critere', function ($query) {
            $query->where('type_champ_form', 'dates');
        })->where('produit_id', $structureProduit->id)->first();

        $timesProduit = StructureProduitCritere::withWhereHas('critere', function ($query) {
            $query->where('type_champ_form', 'times');
        })->where('produit_id', $structureProduit->id)->first();

        if($datesProduit && $timesProduit) {
            $formattedDates = $datesProduit->valeur;
            $formattedTimes = $timesProduit->valeur;
            if ($formattedDates !== null && $formattedTimes !== null) {
                $this->generateStructurePlannings($formattedDates, $formattedTimes, $activite, $structureProduit, $structure);
            }
        }

        return to_route('structures.disciplines.show', ['structure' => $structure, 'discipline' => $activite->discipline ])->with('success', 'Produit ajouté.');

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
            'instructeur_email' => ['nullable', 'email:filter', 'exists:users,email'],
            'instructeur_contact' => ['nullable'],
            'instructeur_phone' => ['nullable'],
        ]);
        $activite = StructureActivite::findOrFail($activite->id);
        $structureProduit = StructureProduit::findOrFail($produit->id);
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

                $critere = LienDisciplineCategorieCritere::withValeurs()->findOrFail($critereId);

                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', true)->where('discipline_categorie_critere_id', $critereId)->first();

                $this->insertCriteresRecursively($structure, $activite, $structureProduit, $critere, $criteresValues, $defaut);
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

                $this->insertSousCriteresRecursively($activite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscritereValue);
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


        $datesProduit = StructureProduitCritere::withWhereHas('critere', function ($query) {
            $query->where('type_champ_form', 'dates');
        })->where('produit_id', $structureProduit->id)->first();

        $timesProduit = StructureProduitCritere::withWhereHas('critere', function ($query) {
            $query->where('type_champ_form', 'times');
        })->where('produit_id', $structureProduit->id)->first();

        if($datesProduit && $timesProduit) {
            $formattedDates = $datesProduit->valeur;
            $formattedTimes = $timesProduit->valeur;

            if ($formattedDates !== null && $formattedTimes !== null) {
                $this->generateStructurePlannings($formattedDates, $formattedTimes, $activite, $structureProduit, $structure);
            }
        }

        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug ])->with('success', 'Produit mis à jour.');

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

        $produit->cat_tarifs()->detach();

        $produit->delete();

        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug ])->with('success', "Le produit a bien été supprimé");
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
        foreach ($originalProduit->cat_tarifs as $tarif) {
            $newProduit->cat_tarifs()->attach($tarif);
        }

        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $activite->discipline->slug ])->with('success', "Le produit a bien été dupliqué");
    }

    private function insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critere, $criteresValues, $defaut)
    {
        if (!empty($criteresValues)) {
            if (isset($criteresValues['valeur'])) {
                // Object
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critere->id ?? $criteresValues['discipline_categorie_critere_id'], $criteresValues['id'] ?? null, $criteresValues['valeur'] ?? null, $defaut);
            } elseif (isset($criteresValues) && $critere->type_champ_form === 'time') {
                // Single hour
                $time = Carbon::parse($criteresValues)->setTimezone('Europe/Paris')->format('H:i');
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critere->id, null, $time, $defaut);
            } elseif (is_array($criteresValues) && $critere->type_champ_form === 'times') {
                // Multiple hours ok
                $this->handleMultipleHours($structure, $structureActivite, $structureProduit, $critere->id, $criteresValues);
            } elseif (is_string($criteresValues) && $critere->type_champ_form === 'date') {
                // Single date
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critere->id, null, Carbon::parse($criteresValues)->setTimezone('Europe/Paris')->format('Y-m-d'), $defaut);
            } elseif (is_array($criteresValues) && $critere->type_champ_form === 'dates') {
                // Multiple dates
                $this->handleMultipleDates($structure, $structureActivite, $structureProduit, $critere->id, $criteresValues);
            } elseif (is_array($criteresValues) && $critere->type_champ_form === 'mois') {
                // Month start/end
                $this->handleMonthStartEnd($structure, $structureActivite, $structureProduit, $critere->id, $criteresValues);
            } elseif (is_string($criteresValues) || is_numeric($criteresValues)) {
                // String or numeric value
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critere->id, null, $criteresValues, null);
            } elseif (is_array($criteresValues) && $critere->type_champ_form === 'range multiple') {
                $value = array_map(function ($value) {
                    return (string)$value;
                }, $criteresValues);

                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critere->id, null, json_encode($value), null);
            } else {
                // Recursive call for nested values
                foreach ($criteresValues as $critereValue) {
                    $this->insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critere, $critereValue, $defaut);
                }
            }
        }
    }

    private function createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, $valeurId = null, $valeur = null)
    {
        StructureProduitCritere::create([
            'structure_id' => $structure->id,
            'discipline_id' => $structureActivite->discipline_id,
            'categorie_id' => $structureActivite->categorie_id,
            'activite_id' => $structureActivite->id,
            'produit_id' => $structureProduit->id,
            'critere_id' => $critereId,
            'valeur_id' => $valeurId,
            'valeur' => $valeur ?? "",
        ]);
    }

    private function insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscritereValue)
    {
        if (!empty($souscritereValue)) {
            if (isset($souscritereValue['id'])) {

                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscritereValue);

            } elseif(is_string($souscritereValue) && $sousCritere->type_champ_form === 'time') {

                $time = Carbon::parse($souscritereValue)->setTimezone('Europe/Paris')->format('H:i');

                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $time);

            } elseif(is_array($souscritereValue) && $sousCritere->type_champ_form === 'times') {

                $horaires = array_map(function ($datetime) {
                    $carbonDate = Carbon::parse($datetime);
                    return $carbonDate->setTimezone('Europe/Paris')->format('H:i');
                }, array_values($souscritereValue));

                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, json_encode($horaires));

            } elseif (is_string($souscritereValue) && $sousCritere->type_champ_form === 'date') {
                // Single date
                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, Carbon::parse($souscritereValue)->setTimezone('Europe/Paris')->format('Y-m-d'));

            } elseif (is_array($souscritereValue) && $sousCritere->type_champ_form === 'dates') {
                // Multiple dates
                $dates = array_map(function ($date) {
                    return Carbon::parse($date)->setTimezone('Europe/Paris')->format('Y-m-d');
                }, $souscritereValue);

                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, json_encode($dates));

            } elseif (is_array($souscritereValue) && $sousCritere->type_champ_form === 'mois') {
                // Mois
                $monthStart = Carbon::parse($souscritereValue['monthStart']);
                $monthEnd = Carbon::parse($souscritereValue['monthEnd']);
                // Extract the year and month, and create the first day of each month
                $dates = [
                    $monthStart->startOfMonth()->format('Y-m-d'),
                    $monthEnd->startOfMonth()->format('Y-m-d')
                ];

                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, json_encode($dates));

            } elseif(is_array($souscritereValue) && $sousCritere->type_champ_form === 'range multiple') {

                $value = array_map(function ($value) {
                    return (string)$value;
                }, $souscritereValue);

                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, json_encode($value));

            } elseif(is_array($souscritereValue)) {

                foreach ($souscritereValue as $subSouscriteresValue) {
                    $this->insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $subSouscriteresValue);
                }

            } else {
                $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscritereValue);
            }
        }
    }

    private function createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscriteresValues)
    {
        $prodCrit = StructureProduitCritere::where('produit_id', $structureProduit->id)->where('critere_id', $critereId)->where('valeur_id', $critereValeurId)->first();

        if(isset($prodCrit)) {
            StructureProduitSousCritere::create([
                'activite_id' => $structureActivite->id,
                'produit_id' => $structureProduit->id,
                'critere_id' => $critereId,
                'prod_crit_id' => $prodCrit->id,
                'critere_valeur_id' => $critereValeurId,
                'sous_critere_id' => $sousCritere->id,
                'sous_critere_valeur_id' => $souscriteresValues['id'] ?? null,
                'valeur' => $souscriteresValues['valeur'] ?? $souscriteresValues,
            ]);
        }
    }

    private function handleSingleValue($structure, $structureActivite, $structureProduit, $critereId, $valeurId, $valeur, $defaut)
    {
        $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, $valeurId ?? ($defaut ? $defaut->id : null), $valeur ?? ($defaut ? $defaut->valeur : null));
    }

    private function handleMultipleHours($structure, $structureActivite, $structureProduit, $critereId, $criteresValues)
    {
        $horaires = array_map(function ($datetime) {
            $carbonDate = Carbon::parse($datetime);
            return $carbonDate->setTimezone('Europe/Paris')->format('H:i');
        }, array_values($criteresValues));

        $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, null, json_encode($horaires));
    }

    private function handleMultipleDates($structure, $structureActivite, $structureProduit, $critereId, $criteresValues)
    {
        $dates = array_map(function ($date) {
            return Carbon::parse($date)->setTimezone('Europe/Paris')->format('Y-m-d');
        }, $criteresValues);

        $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, null, json_encode($dates));
    }

    private function handleMonthStartEnd($structure, $structureActivite, $structureProduit, $critereId, $criteresValues)
    {
        $monthStart = Carbon::parse($criteresValues['monthStart']);
        $monthEnd = Carbon::parse($criteresValues['monthEnd']);

        // Extract the year and month, and create the first day of each month
        $dates = [
            $monthStart->startOfMonth()->format('Y-m-d'),
            $monthEnd->startOfMonth()->format('Y-m-d')
        ];

        $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, null, json_encode($dates));
    }

    private function generateStructurePlannings($formattedDates, $formattedTimes, $structureActivite, $structureProduit, $structure)
    {
        preg_match_all('/(\d{1,2}\s[^\d]+\s\d{4})/', $formattedDates, $matches);
        $datesArray = $matches[0];

        preg_match_all('/(\d{1,2}h\d{2})/', $formattedTimes, $matches);
        $timesArray = $matches[0];

        if (count($timesArray) === 2 && count($datesArray) === 2) {

            //delete previous plannings?
            $structureProduit->plannings()->delete();

            // Create Carbon instances
            $startDate = $this->createCarbonInstance($datesArray[0], 'j F Y');
            $endDate = $this->createCarbonInstance($datesArray[1], 'j F Y');
            $startTime = Carbon::createFromFormat('H\hi', $timesArray[0]);
            $endTime = Carbon::createFromFormat('H\hi', $timesArray[1]);

            $combinedDatePairs = [];
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $startDateTime = $currentDate->copy()->setTime($startTime->hour, $startTime->minute);
                $endDateTime = $currentDate->copy()->setTime($endTime->hour, $endTime->minute);

                $combinedDatePairs[] = [
                    'start' => $startDateTime->toDateTimeString(),
                    'end' => $endDateTime->toDateTimeString(),
                ];

                $currentDate->addDay();
            }
            foreach ($combinedDatePairs as $combinedDatePair) {
                StructurePlanning::create([
                    'structure_id' => $structure->id,
                    'discipline_id' => $structureActivite->discipline_id,
                    'categorie_id' => $structureActivite->categorie_id,
                    'activite_id' => $structureActivite->id,
                    'produit_id' => $structureProduit->id,
                    'title' => $structureActivite->titre,
                    'start' => $combinedDatePair['start'] ?? "",
                    'end' => $combinedDatePair['end'] ?? "",
                ]);
            }
        }
    }

    private function createCarbonInstance($dateString, $format)
    {
        $frenchMonths = [
            'janvier' => 'January',
            'février' => 'February',
            'mars' => 'March',
            'avril' => 'April',
            'mai' => 'May',
            'juin' => 'June',
            'juillet' => 'July',
            'août' => 'August',
            'septembre' => 'September',
            'octobre' => 'October',
            'novembre' => 'November',
            'décembre' => 'December',
        ];

        $englishDateString = str_replace(array_keys($frenchMonths), array_values($frenchMonths), $dateString);
        $cleanedDateString = str_replace(' ', '', $englishDateString);

        return Carbon::createFromFormat($format, $cleanedDateString)->locale('fr_FR');
    }
}
