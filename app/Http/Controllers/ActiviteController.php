<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use App\Models\LiensDisCatCritValSsCrit;
use App\Models\StructureProduitSousCritere;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureActiviteResource;
use App\Models\LienDisciplineCategorieCritereValeur;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

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

        $structureDiscipline = $structure->disciplines()->attach($discipline->id, ['nb_produits' => 1]);

        // structureCategorie
        $validatedData = request()->validate([
            'categories_id' => ['required', 'array', Rule::exists('categories', 'id')]
        ]);


        foreach ($validatedData['categories_id'] as $category_id) {

            $disCat = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('categorie_id', $category_id)->firstOrfail();

            $structureActiviteCategorie = $structure->categories()->attach($disCat->id, [
                'discipline_id' => $discipline->id,
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

    public function show(Request $request, StructureActivite $activite, string $slug, ?string $produit = null): Response
    {
        $filters = $request->only(['crit', 'ssCrit']);
        $page = $request->input('page', 1);

        if ($produit !== null) {
            $selectedProduit = StructureProduitResource::make(StructureProduit::withRelations()->find($produit));
        }

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

        $produits = $activite->produits()->withRelations()->filter($filters)->paginate(4, ['*'], 'prodpage', $page);

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


        $currentRoute = [
                'name' => 'structures.activites.show',
                'params' => [
                    'activite' => $activite->id,
                    'slug' => $activite->slug_title,
                ]
            ];


        return Inertia::render('Structures/Activites/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'activite' => fn () => StructureActiviteResource::make($activite),
            'produits' => fn () => StructureProduitResource::collection($produits) ,
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'selectedProduit' => fn () => $selectedProduit ?? null,
            'activiteSimilaires' => fn () => StructureActiviteResource::collection($activiteSimilaires),
            'filters' => $filters ?? null,
            'currentRoute' => $currentRoute,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Structure $structure, StructureActivite $activite): RedirectResponse
    {
        if (!Gate::allows('update-structure', $structure)) {
            return to_route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de modifier cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $request->validate([
            'titre' => 'required|string|min:3',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // $structureActivite = StructureActivite::with(['structure','categorie', 'discipline'])->where('structure_id', $structure->id)->findOrFail($activite->id);

        if ($request->hasFile('image')) {
            if($activite->image) {
                Storage::disk('public')->delete($activite->image);
            }
            $path = $request->file('image')->store('structures/' . $structure->id . '/activites/' . $activite->id, 'public');
            $activite->update(['image' => $path]);
        }

        $activite->update([
            'titre' => $request->titre,
            'description' => $request->description,
            // 'actif' => 1,
        ]);

        return to_route('structures.disciplines.show', ['structure' => $structure, 'discipline' => $activite->discipline ])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, StructureActivite $activite): RedirectResponse
    {

        $discipline = $activite->discipline;
        $categorieId = $activite->categorie->id;

        if ($activite->image) {
            Storage::disk('public')->delete($activite->image);
        }

        $activite->delete();

        $catCount = $structure->activites()->where('categorie_id', $categorieId)->count();
        if($catCount < 1) {

            $structure->categories()->where('liens_disciplines_categories.categorie_id', $categorieId)->detach($categorieId);

            $disCount = $structure->activites()->where('discipline_id', $discipline->id)->count();
            if($disCount < 1) {
                $structure->disciplines()->where('discipline_id', $discipline->id)->detach($discipline->id);

                return to_route('structures.disciplines.index', ['structure' => $structure->slug])->with('success', 'l\'activité a été supprimée.');
            }
        }

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

        return to_route('structures.categories.show', ['structure' => $structure, 'discipline' => $structureActivite->discipline, 'categorie' => $structureActivite->categorie])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');
    }

    public function newactivitystore(Request $request, Structure $structure): RedirectResponse
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

        $structure = Structure::with([
            'adresses',
            'disciplines',
            'disciplines.str_categories',
            'categories',
        ])->findOrFail($structure->id);

        $discipline = ListDiscipline::find($request->discipline_id);

        $categorie = LienDisciplineCategorie::with('discipline')->find($request->categorie_id);

        $strDiscipline = $structure->disciplines->firstWhere('id', $discipline->id);

        if($strDiscipline) {
            $strCategories = $strDiscipline->str_categories;
            if (!$strCategories->contains($categorie)) {
                $structure->categories()->attach($categorie->id, [
                    'discipline_id' => $discipline->id,
                ]);
            }
        }

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

                $critere = LienDisciplineCategorieCritere::withValeurs()->findOrFail($critereId);

                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', true)->where('discipline_categorie_critere_id', $critereId)->first() ?? null;

                $this->insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critere, $criteresValues, $defaut);
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

                $this->insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritere, $souscritereValue);
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
