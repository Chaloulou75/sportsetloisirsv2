<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Rules\NestedArrays;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use App\Models\LiensDisCatCritValSsCrit;
use App\Models\StructureProduitSousCritere;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class ActiviteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $structure): RedirectResponse
    {
        $validated = request()->validate([
            'structure_id' => ['required', Rule::exists(Structure::class, 'id')],
            'discipline_id' => ['required', Rule::exists(ListDiscipline::class, 'id')],
        ]);

        $structure = Structure::findOrFail($validated['structure_id']);
        $discipline = ListDiscipline::findOrFail($validated['discipline_id']);

        $structureAdresse = StructureAddress::where('structure_id', $structure->id)->firstOrfail();

        // check if structure_id and discipline_id combined exists in StructureDiscipline
        $exists = StructureDiscipline::where('structure_id', $structure->id)
                                    ->where('discipline_id', $discipline->id)
                                    ->exists();
        if($exists) {
            return to_route('structures.disciplines.index', $structure)->with('error', 'Cette discipline est déjà associée à cette structure.');
        }

        $structureDiscipline = $structure->disciplines()->create([
            'discipline_id' => $discipline->id,
            'nb_produits' => 1,
        ]);

        // structureCategorie
        $validatedData = request()->validate([
            'categories_id' => ['required', 'array', Rule::exists('categories', 'id')]
        ]);

        foreach ($validatedData['categories_id'] as $category_id) {

            $disCat = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('categorie_id', $category_id)->firstOrfail();

            $structureActiviteCategorie = $structure->categories()->create([
                'discipline_id' => $discipline->id,
                'categorie_id' => $disCat->id,
            ]);

            $titre = $disCat->nom_categorie_pro . ' de ' . $discipline->name ;

            $activite = $structure->activites()->create([
                'discipline_id' => $discipline->id,
                'categorie_id' => $disCat->id,
                'titre' => $titre,
                'description' => "",
                'image' => "",
                "actif" => true,
            ]);

            $produit = $structure->produits()->create([
                'discipline_id' => $discipline->id,
                'categorie_id' => $disCat->id,
                'activite_id' => $activite->id,
                "actif" => true,
                'lieu_id' => $structureAdresse->id,
                'reservable' => false,
            ]);

            $criteres = LienDisciplineCategorieCritere::withValeurs()
            ->where('discipline_id', $discipline->id)
            ->where('categorie_id', $disCat->id)
            ->where('visible_back', true)
            ->where('visible_front', true)
            ->get();

            foreach($criteres as $critere) {
                $defaut = $critere->valeurs->where('defaut', 1)
                ->where('discipline_categorie_critere_id', $critere->id)
                ->first();
                if ($defaut !== null) {
                    $this->createStructureProduitCritere($structure, $activite, $produit, $critere->id, $defaut->id, $defaut->valeur);
                }
            }
        }

        return to_route('structures.disciplines.show', ['structure' => $structure , 'discipline' => $discipline])->with('success', 'Activité créée, vous pouvez ajouter d\'autres activités à votre structure.');
    }

    public function show(StructureActivite $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::withRelations()->find(request()->produit);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $activite = StructureActivite::with([
            'structure',
            'structure.adresses',
            'instructeurs'
        ])->find($activite->id); //withRelations()->

        $produits = $activite->produits()->withRelations()->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $activite->discipline_id)
                ->where('categorie_id', $activite->categorie_id)
                ->where('visible_front', true)
                ->get();

        $activiteSimilaires = StructureActivite::with([
                'produits',
                'produits.criteres',
                'produits.criteres.sous_criteres',
                'produits.adresse'
            ])
            ->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'activite' => fn () => $activite,
            'produits' => fn () => $produits,
            'criteres' => fn () => $criteres,
            'selectedProduit' => fn () => $selectedProduit,
            'activiteSimilaires' => fn () => $activiteSimilaires
        ]);
    }

    /**
     * Show the form for editing a resource.
     */
    public function edit(Structure $structure, $activite)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Structure $structure, $activite): RedirectResponse
    {
        if (!Gate::allows('update-structure', $structure)) {
            return to_route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de modifier cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $request->validate([
                'titre' => 'required|string|min:3',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);

        $structureActivite = StructureActivite::with(['structure','categorie', 'discipline'])->where('structure_id', $structure->id)->findOrFail($activite);

        if ($request->hasFile('image')) {
            if($structureActivite->image) {
                Storage::disk('public')->delete($structureActivite->image);
            }
            $path = $request->file('image')->store('structures/' . $structure->id . '/activites/' . $structureActivite->id, 'public');
            $structureActivite->update(['image' => $path]);
        }

        $structureActivite->update([
            'titre' => $request->titre,
            'description' => $request->description,
            // 'actif' => 1,
        ]);

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $structureActivite->discipline->slug, 'categorie' => $structureActivite->categorie->id, ])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $activite): RedirectResponse
    {
        $activite = StructureActivite::findOrFail($activite);

        $discipline = $activite->discipline;
        $categorieId = $activite->categorie->id;

        $produits = StructureProduit::where('activite_id', $activite->id)->get();

        $criteres = StructureProduitCritere::with('sous_criteres')->where('activite_id', $activite->id)->get();

        $souscriteres = StructureProduitSousCritere::where('activite_id', $activite->id)->get();

        $plannings = StructurePlanning::where('activite_id', $activite->id)->get();

        if($plannings->isNotEmpty()) {
            foreach($plannings as $planning) {
                $planning->delete();
            }
        }

        if($souscriteres->isNotEmpty()) {
            foreach($souscriteres as $souscritere) {
                $souscritere->delete();
            }
        }

        if($criteres->isNotEmpty()) {
            foreach($criteres as $critere) {
                $critere->delete();
            }
        }

        if($produits->isNotEmpty()) {
            foreach($produits as $produit) {
                $produit->delete();
            }
        }

        if($activite->image) {
            Storage::disk('public')->delete($activite->image);
        }

        if($activite) {
            $activite->delete();
        }

        $structureCategories = StructureCategorie::doesntHave('activites')
        ->where('structure_id', $structure->id)
        ->where('discipline_id', $discipline->id)
        ->where('categorie_id', $categorieId)
        ->get();

        if($structureCategories->isNotEmpty()) {
            foreach($structureCategories as $structureCategorie) {
                $structureCategorie->delete();
            }
        };

        $structureDisciplines = StructureDiscipline::doesntHave('categories')
        ->where('structure_id', $structure->id)
        ->where('discipline_id', $discipline->id)
        ->get();

        if($structureDisciplines->isNotEmpty()) {
            foreach($structureDisciplines as $structureDiscipline) {
                $structureDiscipline->delete();
            }
        };


        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline ])->with('success', 'l\'activité a été supprimée.');

    }

    public function toggleactif(Request $request, Structure $structure, $activite): RedirectResponse
    {
        $request->validate([
            'actif' => 'required|boolean',
        ]);

        $structureActivite = StructureActivite::with([
            'structure',
            'categorie',
            'discipline'
        ])->where('structure_id', $structure->id)
            ->find($activite);

        $structureActivite->update(['actif' => $request->actif]);

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $structureActivite->discipline->slug, 'categorie' => $structureActivite->categorie->id])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');
    }

    public function newactivitystore(Request $request, Structure $structure, $discipline): RedirectResponse
    {
        $request->validate([
            'structure_id' => ['required', Rule::exists(Structure::class, 'id')],
            'discipline_id' => ['required', Rule::exists(ListDiscipline::class, 'id')],
            'categorie_id' => ['required', Rule::exists(LienDisciplineCategorie::class, 'id')],
            'titre' => 'nullable|string|min:3',
            'description' => 'nullable|string|min:5',
            'image' => 'nullable|image|max:2048',
            'actif' => 'required|boolean',
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

        $structure = Structure::with('adresses')->findOrFail($structure->id);

        $categorie = LienDisciplineCategorie::with('discipline')->find($request->categorie_id);

        $structureActivite = $structure->activites()->create([
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'titre' => $request->titre ?? $categorie->nom_categorie_pro . ' de ' . $categorie->discipline->name,
            'description' => $request->description,
            'image' => "",
            "actif" => true,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('structures/' . $structure->id . '/activites/' . $structureActivite->id, 'public');
            $structureActivite->update(['image' => $path]);
        }

        if($request->address) {
            //check if address exist
            foreach($structure->adresses as $address) {
                if (($address->address_lat === $request->address_lat) && ($address->address_lng === $request->address_lng)) {
                    return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline])->with('error', 'Cette adresse existe déjà dans votre liste d\'adresses');
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

        $structureProduit = $structureActivite->produits()->create([
            'structure_id' => $structure->id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            "actif" => true,
            'lieu_id' => $structureAddressId,
            'horaire_id' => null,
            'reservable' => false,
        ]);

        $criteresValuesSets = $request->criteres;

        if (isset($criteresValuesSets)) {
            foreach ($criteresValuesSets as $critereId => $criteresValues) {

                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', true)->where('discipline_categorie_critere_id', $critereId)->first() ?? null;

                $this->insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critereId, $criteresValues, $defaut);
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

                $this->insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
            }
        }

        if($request->instructeur_email) {
            $user = User::where('email', $request->instructeur_email)->firstOrFail();
            $instructeur = $structureActivite->instructeurs()->attach($user, [
                'contact' => $request->instructeur_contact,
                'email' => $request->instructeur_email,
                'phone' => $request->instructeur_phone,
            ]);
        }

        //insertion ds le planning dates et heures
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
                $this->generateStructurePlannings($formattedDates, $formattedTimes, $structureActivite, $structureProduit, $structure);
            }
        }

        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline])->with('success', 'Activité ajoutée avec succès, ajoutez d\'autres activités à votre structure.');
    }

    private function insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critereId, $criteresValues, $defaut)
    {
        if (!empty($criteresValues)) {
            if (isset($criteresValues['valeur'])) {
                // Object
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critereId, $criteresValues['id'] ?? null, $criteresValues['valeur'] ?? null, $defaut);
            } elseif (isset($criteresValues['hours'], $criteresValues['minutes'])) {
                // Single hour
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critereId, null, Carbon::createFromTime($criteresValues['hours'], $criteresValues['minutes'])->format('H:i'), $defaut);
            } elseif (is_array($criteresValues) && isset($criteresValues[0]['hours'], $criteresValues[0]['minutes'])) {
                // Multiple hours
                $this->handleMultipleHours($structure, $structureActivite, $structureProduit, $critereId, $criteresValues);
            } elseif (is_string($criteresValues) && strtotime($criteresValues) !== false) {
                // Single date
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critereId, null, Carbon::parse($criteresValues)->setTimezone('Europe/Paris')->format('Y-m-d'), $defaut);
            } elseif (is_array($criteresValues) && count($criteresValues) > 0 && is_string($criteresValues[0]) && strtotime($criteresValues[0]) !== false) {
                // Multiple dates
                $this->handleMultipleDates($structure, $structureActivite, $structureProduit, $critereId, $criteresValues);
            } elseif (is_array($criteresValues) && isset($criteresValues[0]['month']) && isset($criteresValues[0]['year'])) {
                // Month start/end
                $this->handleMonthStartEnd($structure, $structureActivite, $structureProduit, $critereId, $criteresValues);
            } elseif (is_string($criteresValues) || is_numeric($criteresValues)) {
                // String or numeric value
                $this->handleSingleValue($structure, $structureActivite, $structureProduit, $critereId, null, $criteresValues, null);
            } else {
                // Recursive call for nested values
                foreach ($criteresValues as $critereValue) {
                    $this->insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critereId, $critereValue, $defaut);
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

    private function insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue)
    {
        if (isset($souscritereValue['id'])) {
            $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
        } elseif(is_array($souscritereValue)) {
            foreach ($souscritereValue as $subSouscriteresValue) {
                $this->insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $subSouscriteresValue);
            }
        } else {
            $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
        }
    }

    private function createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscriteresValues)
    {
        $prodCrit = StructureProduitCritere::where('produit_id', $structureProduit->id)->where('critere_id', $critereId)->where('valeur_id', $critereValeurId)->first();

        if(isset($prodCrit)) {
            StructureProduitSousCritere::create([
                'activite_id' => $structureActivite->id,
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

    private function handleSingleValue($structure, $structureActivite, $structureProduit, $critereId, $valeurId, $valeur, $defaut)
    {
        $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, $valeurId ?? ($defaut ? $defaut->id : null), $valeur ?? ($defaut ? $defaut->valeur : null));
    }

    private function handleMultipleHours($structure, $structureActivite, $structureProduit, $critereId, $criteresValues)
    {
        $horaires = array_map(function ($horaire) {
            return Carbon::createFromTime($horaire['hours'], $horaire['minutes'])->format('H:i');
        }, $criteresValues);

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
        $dates = array_map(function ($dateInfo) {
            $month = $dateInfo['month'];
            $year = $dateInfo['year'];
            return Carbon::create($year, $month, 1, 0, 0, 0)->format('Y-m-d');
        }, $criteresValues);

        $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, null, json_encode($dates));
    }

    private function generateStructurePlannings($formattedDates, $formattedTimes, $structureActivite, $structureProduit, $structure)
    {
        preg_match_all('/(\d{1,2}\s[^\d]+\s\d{4})/', $formattedDates, $matches);
        $datesArray = $matches[0];

        preg_match_all('/(\d{1,2}h\d{2})/', $formattedTimes, $matches);
        $timesArray = $matches[0];

        if (count($timesArray) === 2 && count($datesArray) === 2) {
            function createCarbonInstance($dateString, $format)
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

            // Create Carbon instances
            $startDate = createCarbonInstance($datesArray[0], 'j F Y');
            $endDate = createCarbonInstance($datesArray[1], 'j F Y');
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
}
