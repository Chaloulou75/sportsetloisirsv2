<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\ListeTarifType;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\ProductReservation;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource and Show the form for creating a new resource.
     */
    public function index(Structure $structure)
    {
        // $structure = Structure::with([
        //                 'disciplines',
        //                 'activites',
        //                 'adresses' => function ($query) {
        //                     $query->latest();
        //                 },
        //                 'produits',
        //                 'tarifs',
        //                 'tarifs.tarifType',
        //                 'tarifs.structureTarifTypeInfos',
        //                 'tarifs.structureTarifTypeInfos.tarifTypeAttribut'
        //             ])->select(['id', 'name', 'slug'])
        //             ->where('id', $structure->id)
        //             ->first();

        // $activites = StructureActivite::with([
        //     'structure:id,name,slug,presentation_courte',
        //     'categorie:id,nom_categorie_pro',
        //     'discipline:id,name',
        //     'plannings',
        //     'produits',
        //     'produits.adresse',
        //     'produits.criteres',
        //     'produits.criteres.critere',
        //     'produits.horaire',
        //     'produits.tarifs',
        //     'produits.tarifs.structureTarifTypeInfos',
        //     'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
        //     'produits.tarifs.tarifType'
        // ])
        //     ->where('structure_id', $structure->id)
        //     ->latest()
        //     ->get();

        // $tarifTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type', 'slug'])->get();


        // $allReservationsCount = ProductReservation::with('produit', function ($query) use ($structure) {
        //     $query->where('structure_id', $structure->id);
        // })->count();
        // $pendingReservationsCount = ProductReservation::with('produit', function ($query) use ($structure) {
        //     $query->where('structure_id', $structure->id);
        // })->where('pending', true)->count();
        // $confirmedReservationsCount = ProductReservation::with('produit', function ($query) use ($structure) {
        //     $query->where('structure_id', $structure->id);
        // })->where('confirmed', true)
        //     ->count();


        // $actByDiscAndCategorie = $activites->groupBy('discipline.name')->map(function ($disciplineCategories) {
        //     $categories = $disciplineCategories->groupBy('categorie.nom_categorie_pro')->map(function ($categorieItems) {
        //         return [
        //                     'activite_id' => $categorieItems->first()->id,
        //                     'categorie_id' => $categorieItems->first()->categorie->id,
        //                     'name' => $categorieItems->first()->categorie->nom_categorie_pro ?? 'Sans Catégorie',
        //                     'count' => $categorieItems->count(),
        //                 ];
        //     })->sortByDesc('count');

        //     return [
        //         'id' => $disciplineCategories->first()->id,
        //         'discipline_id' => $disciplineCategories->first()->discipline->id,
        //         'disciplineName' => $disciplineCategories->first()->discipline->name,
        //         'count' => $disciplineCategories->count(),
        //         'categories' => $categories,
        //     ];
        // });

        // $activiteForTarifs = StructureActivite::with([
        //             'structure:id,name,slug',
        //             'categorie:id,nom_categorie_pro',
        //             'discipline:id,name',
        //             'produits',
        //             'produits.tarifs',
        //             'produits.tarifs.structureTarifTypeInfos',
        //             'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut'])
        //             ->where('structure_id', $structure->id)
        //             ->latest()
        //             ->get()
        //             ->groupBy('discipline.id')
        //             ->map(function ($disciplineActivites, $disciplineId) {
        //                 return [
        //                     'id' => $disciplineId,
        //                     'disciplineName' => $disciplineActivites->first()->discipline->name,
        //                     'categories' => $disciplineActivites->groupBy('categorie.id')->map(function ($categorieItems, $categoryId) {
        //                         $activites = $categorieItems->map(function ($activiteItem) {
        //                             return [
        //                                 'id' => $activiteItem->id,
        //                                 'titre' => $activiteItem->titre,
        //                                 'disciplineId' => $activiteItem->discipline_id,
        //                                 'categorieId' => $activiteItem->categorie_id,
        //                                 'produits' => $activiteItem->produits->map(function ($produitItem) {
        //                                     return [
        //                                         'id' => $produitItem->id,
        //                                         'disciplineId' => $produitItem->discipline_id,
        //                                         'categorieId' => $produitItem->categorie_id,
        //                                         'activiteId' => $produitItem->activite_id,
        //                                         'tarifs' => $produitItem->tarifs->map(function ($tarifItem) {
        //                                             return [
        //                                                 'id' => $tarifItem->id,
        //                                                 'typeId' => $tarifItem->type_id,
        //                                                 'titre' => $tarifItem->titre,
        //                                                 'description' => $tarifItem->description,
        //                                                 'amount' => $tarifItem->amount,
        //                                                 'produits' => $tarifItem->produits,
        //                                                 'infos' => $tarifItem->structureTarifTypeInfos->map(function ($infoItem) {
        //                                                     return [
        //                                                         'id' => $infoItem->id,
        //                                                         'attribut_id' => $infoItem->attribut_id,
        //                                                         'valeur' => $infoItem->valeur,
        //                                                         'unite' => $infoItem->unite,
        //                                                         'tarifTypeAttribut' => $infoItem->tarifTypeAttribut
        //                                                     ];
        //                                                 }),
        //                                             ];
        //                                         }),
        //                                     ];
        //                                 }),
        //                             ];
        //                         });
        //                         return [
        //                             'id' => $categoryId,
        //                             'disciplineId' => $categorieItems->first()->discipline->id,
        //                             'name' => $categorieItems->first()->categorie->nom_categorie_pro ?? 'Sans Catégorie',
        //                             'activites' => $activites,
        //                         ];
        //                     }),
        //                 ];
        //             });

        // $categories = Categorie::with('disciplines')->select(['id', 'nom', 'ico'])->get();

        // $dejaUsedDisciplines = $structure->disciplines->unique()->pluck('discipline_id');

        // $listDisciplines = ListDiscipline::with(['categories'])->select(['id', 'name', 'slug'])->get();

        // return Inertia::render('Structures/Activites/Index', [
        //     'structure' => $structure,
        //     'categories' => $categories,
        //     'dejaUsedDisciplines' => $dejaUsedDisciplines,
        //     'listDisciplines' => $listDisciplines,
        //     'activites' => $activites,
        //     'tarifTypes' => $tarifTypes,
        //     'actByDiscAndCategorie' => $actByDiscAndCategorie,
        //     'activiteForTarifs' => $activiteForTarifs,
        //     'allReservationsCount' => $allReservationsCount,
        //     'confirmedReservationsCount' => $confirmedReservationsCount,
        //     'pendingReservationsCount' => $pendingReservationsCount,
        //     'can' => [
        //         'update' => optional(Auth::user())->can('update', $structure),
        //         'delete' => optional(Auth::user())->can('delete', $structure),
        //     ]
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Structure $structure)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $structure)
    {
        $validated = request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
        ]);

        $structure = Structure::where('id', $validated['structure_id'])->firstOrFail();

        $structureAdresse = StructureAddress::where('structure_id', $structure->id)->firstOrfail();

        // check if structure_id and discipline_id combined exists in StructureDiscipline
        $exists = StructureDiscipline::where('structure_id', $validated['structure_id'])
                                    ->where('discipline_id', $validated['discipline_id'])
                                    ->exists();
        if($exists) {
            return to_route('structures.disciplines.index', $structure)->with('error', 'Cette discipline est déjà associée à cette structure.');
        }

        $structureDiscipline = StructureDiscipline::create([
            'structure_id' => $validated['structure_id'],
            'discipline_id' => $validated['discipline_id'],
        ]);
        $structureDiscipline->increment('nb_produits');

        // structureActiviteCategorie
        $validatedData = request()->validate([
            'categories_id' => ['required', 'array', Rule::exists('categories', 'id')]
        ]);

        foreach ($validatedData['categories_id'] as $category_id) {

            $lienActCat = LienDisciplineCategorie::where('discipline_id', $validated['discipline_id'])->where('categorie_id', $category_id)->firstOrfail();

            $validated['categorie_id'] = $lienActCat->id;
            $structureActiviteCategorie = StructureCategorie::create([
                'structure_id' => $validated['structure_id'],
                'discipline_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
            ]);
            $structureActiviteCategorie->with('discipline')->first();

            $titre = $lienActCat->nom_categorie_pro .' de '. $structureActiviteCategorie->discipline->name ;

            $structureActivite = StructureActivite::create([
                'structure_id' => $validated['structure_id'],
                'discipline_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
                'titre' => $titre,
                'description' => "",
                'image' => "",
                "actif" => 1,
            ]);

            $produit = StructureProduit::create([
                'structure_id' => $validated['structure_id'],
                'discipline_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
                'activite_id' => $structureActivite->id,
                "actif" => 1,
                'lieu_id' => $structureAdresse->id,
                'reservable' => 0,
            ]);

            $disciplineCategorieCriteres = LienDisciplineCategorieCritere::where('discipline_id', $validated['discipline_id'])->where('categorie_id', $validated['categorie_id'])->get();

            foreach($disciplineCategorieCriteres as $disciplineCategorieCritere) {
                $ActCatCriVal = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $disciplineCategorieCritere->id)->first();

                $critere = StructureProduitCritere::create([
                    'structure_id' => $validated['structure_id'],
                    'discipline_id' => $validated['discipline_id'],
                    'categorie_id' => $validated['categorie_id'],
                    'activite_id' => $structureActivite->id,
                    'produit_id' => $produit->id,
                    'critere_id' => $disciplineCategorieCritere->id,
                    'valeur' => $ActCatCriVal->valeur ?? 'Tous',
                ]);
            }
        }

        return to_route('structures.disciplines.index', $structure)->with('success', 'Activité créée, vous pouvez ajouter d\'autres activités à votre structure.');
    }

    public function show($activite)
    {
        $familles = Famille::with([
                    'disciplines' => function ($query) {
                        $query->whereHas('structures');
                    }
                ])
                ->whereHas('disciplines', function ($query) {
                    $query->whereHas('structures');
                })
                ->select(['id', 'name', 'slug'])
                ->get();

        $activite = StructureActivite::with([
            'structure',
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
            'produits.plannings',
        ])->where('id', $activite)->first();

        $structure = $activite->structure()->with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    ])
                    ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
                    ->first();

        $logoUrl = asset($structure->logo);

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
                ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
                ->get();

        $activiteSimilaires = StructureActivite::with([
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.plannings'
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
                    'structure' => $structure,
                    'familles' => $familles,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'activiteSimilaires' => $activiteSimilaires
        ]);
    }

    /**
     * Show the form for editing a resource.
     */
    public function edit(Structure $structure, $activite)
    {
        if (! Gate::allows('update-structure', $structure)) {
            return to_route('structures.disciplines.index', $structure->slug)->with('error', 'Vous n\'avez pas la permission d\'éditer cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $allReservationsCount = ProductReservation::with('produit', function ($query) use ($structure) {
            $query->where('structure_id', $structure->id);
        })->count();
        $pendingReservationsCount = ProductReservation::with('produit', function ($query) use ($structure) {
            $query->where('structure_id', $structure->id);
        })->where('pending', true)->count();
        $confirmedReservationsCount = ProductReservation::with('produit', function ($query) use ($structure) {
            $query->where('structure_id', $structure->id);
        })->where('confirmed', true)
            ->count();


        $structure = Structure::with([
            'adresses' => function ($query) {
                $query->latest();
            },
            'produits',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'tarifs.structureTarifTypeInfos.tarifTypeAttribut'
        ])
        ->select(['id', 'name', 'slug'])
        ->where('slug', $structure->slug)
        ->first();

        $activite = StructureActivite::with(['structure','categorie', 'discipline'])
                        ->where('structure_id', $structure->id)
                        ->where('id', $activite)
                        ->withCount('categorie')
                        ->first();

        $categoriesListByDiscipline = LienDisciplineCategorie::where('discipline_id', $activite->discipline->id)->get();

        $structureActivites = StructureActivite::with([
            'structure:id,name,slug,presentation_courte',
            'categorie:id,nom_categorie_pro',
            'discipline:id,name',
            'plannings',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.horaire',
            'produits.tarifs',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
            'produits.tarifs.tarifType'
            ])
            ->where('structure_id', $structure->id)
            ->where('discipline_id', $activite->discipline->id)
            ->orderBy('categorie_id')
            ->latest()
            ->get();

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])->where('discipline_id', $activite->discipline->id)->get();

        $tarifTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type', 'slug'])->get();

        $activiteForTarifs = StructureActivite::with([
            'structure:id,name,slug',
            'categorie:id,nom_categorie_pro',
            'discipline:id,name',
            'produits',
            'produits.tarifs',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut'])
            ->where('structure_id', $structure->id)
            ->latest()
            ->get()
            ->groupBy('discipline.id')
            ->map(function ($disciplineActivites, $disciplineId) {
                return [
                    'id' => $disciplineId,
                    'disciplineName' => $disciplineActivites->first()->discipline->name,
                    'categories' => $disciplineActivites->groupBy('categorie.id')->map(function ($categorieItems, $categoryId) {
                        $activites = $categorieItems->map(function ($activiteItem) {
                            return [
                                'id' => $activiteItem->id,
                                'titre' => $activiteItem->titre,
                                'disciplineId' => $activiteItem->discipline_id,
                                'categorieId' => $activiteItem->categorie_id,
                                'produits' => $activiteItem->produits->map(function ($produitItem) {
                                    return [
                                        'id' => $produitItem->id,
                                        'disciplineId' => $produitItem->discipline_id,
                                        'categorieId' => $produitItem->categorie_id,
                                        'activiteId' => $produitItem->activite_id,
                                        'tarifs' => $produitItem->tarifs->map(function ($tarifItem) {
                                            return [
                                                'id' => $tarifItem->id,
                                                'typeId' => $tarifItem->type_id,
                                                'titre' => $tarifItem->titre,
                                                'description' => $tarifItem->description,
                                                'amount' => $tarifItem->amount,
                                                'produits' => $tarifItem->produits,
                                                'infos' => $tarifItem->structureTarifTypeInfos->map(function ($infoItem) {
                                                    return [
                                                        'id' => $infoItem->id,
                                                        'attribut_id' => $infoItem->attribut_id,
                                                        'valeur' => $infoItem->valeur,
                                                        'unite' => $infoItem->unite,
                                                        'tarifTypeAttribut' => $infoItem->tarifTypeAttribut
                                                    ];
                                                }),
                                            ];
                                        }),
                                    ];
                                }),
                            ];
                        });
                        return [
                            'id' => $categoryId,
                            'disciplineId' => $categorieItems->first()->discipline->id,
                            'name' => $categorieItems->first()->categorie->nom_categorie_pro ?? 'Sans Catégorie',
                            'activites' => $activites,
                        ];
                    }),
                ];
            });

        return Inertia::render('Structures/Activites/Edit', [
            'structure' => $structure,
            'activite' => $activite,
            'structureActivites' => $structureActivites,
            'criteres' => $criteres,
            'categoriesListByDiscipline' => $categoriesListByDiscipline,
            'tarifTypes' => $tarifTypes,
            'activiteForTarifs' => $activiteForTarifs,
            'allReservationsCount' => $allReservationsCount,
            'confirmedReservationsCount' => $confirmedReservationsCount,
            'pendingReservationsCount' => $pendingReservationsCount,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Structure $structure, $activite)
    {

        if (! Gate::allows('update-structure', $structure)) {
            return to_route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de modifier cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $request->validate([
                'titre' => 'required|string|min:3',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                // 'actif' => 'nullable|boolean',
            ]);

        $structureActivite = StructureActivite::with(['structure','categorie', 'discipline'])
                                    ->where('structure_id', $structure->id)
                                    ->where('id', $activite)
                                    ->first();


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/structures/' . $structure->id . '/activites/' . $structureActivite->id);

            $url = Storage::url($path);
            $structureActivite->update(['image' => $url]);
        }

        $structureActivite->update([
            'titre' => $request->titre,
            'description' => $request->description,
            // 'actif' => 1,
        ]);

        return to_route('structures.activites.edit', ['structure' => $structure->slug, 'activite' => $activite])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $activite): RedirectResponse
    {
        $activite = StructureActivite::where('id', $activite)->first();

        $produits = StructureProduit::where('activite_id', $activite->id)->get();

        $criteres = StructureProduitCritere::where('activite_id', $activite->id)->get();

        $plannings = StructurePlanning::where('activite_id', $activite->id)->get();

        if($plannings->isNotEmpty()) {
            foreach($plannings as $planning) {
                $planning->delete();
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

        if($activite->image !== null) {
            Storage::delete($activite->image);
        }

        if($activite) {
            $activite->delete();
        }

        return to_route('structures.activites.index', ['structure' => $structure->slug ])->with('success', 'l\'activité a été supprimée.');

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
            ->where('id', $activite)
            ->first();

        $structureActivite->update(['actif' => $request->actif]);

        return to_route('structures.activites.edit', ['structure' => $structure->slug, 'activite' => $activite]);
    }

    public function newactivitystore(Request $request, Structure $structure, $discipline): RedirectResponse
    {
        $request->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'categorie_id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'titre' => 'nullable|string|min:3',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'actif' => 'required|boolean',
            'criteres' => 'nullable',
            'adresse' => ['nullable', Rule::exists('structure_adresse', 'id')],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'date' => ['nullable'],
            'time' => ['nullable'],
        ]);

        $structure = Structure::with('adresses')->where('id', $request->structure_id)->firstOrfail();

        $categorieDiscName = LienDisciplineCategorie::with('discipline')->where('id', $request->categorie_id)->first();

        //check if address exist
        if($structure->id === $request->structure_id) {
            foreach($structure->adresses as $address) {
                if (($address->address_lat === $request->address_lat) && ($address->address_lng === $request->address_lng)) {
                    return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline])->with('error', 'Cette adresse existe déjà dans votre liste d\'adresses');
                }
            }
        }

        if($request->date || $request->time) {

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
                'dayopen' => $dayopen ?? "",
                'dayclose' => $dayclose ?? "",
                'houropen' => $houropen ?? "",
                'hourclose' => $hourclose ?? "",
            ]);
        }

        $structureActivite = StructureActivite::create([
            'structure_id' => $structure->id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'titre' => $request->titre ?? $categorieDiscName->nom_categorie_pro.' de '. $categorieDiscName->discipline->name,
            'description' => $request->description,
            'image' => "",
            "actif" => 1,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/structures/' . $structure->id . '/activites/' . $structureActivite->id);
            $url = Storage::url($path);
            $structureActivite->update(['image' => $url]);
        }

        $structureProduit = StructureProduit::create([
            'structure_id' => $structure->id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'activite_id' => $structureActivite->id,
            "actif" => 1,
            'lieu_id' => $request->adresse ?? $structure->adresses->first()->id,
            'horaire_id' => $dayTime->id ?? null,
            // 'tarif_id' => $structureTarif->id,
            'reservable' => 0,
        ]);

        $criteres = LienDisciplineCategorieCritere::with('valeurs')->where('discipline_id', $request->discipline_id)->where('categorie_id', $request->categorie_id)->get();

        $criteresValues = $request->criteres;

        if(isset($criteresValues)) {
            foreach ($criteresValues as $key => $critereValue) {
                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $key)->first();

                if (isset($critereValue)) {
                    if (is_array($critereValue)) {
                        foreach ($critereValue as $value) {
                            $structureProduitCriteres = StructureProduitCritere::create([
                                'structure_id' => $structure->id,
                                'discipline_id' => $request->discipline_id,
                                'categorie_id' => $request->categorie_id,
                                'activite_id' => $structureActivite->id,
                                'produit_id' => $structureProduit->id,
                                'critere_id' => $key,
                                'valeur' => $value,
                            ]);
                        }
                    } else {
                        $structureProduitCriteres = StructureProduitCritere::create([
                            'structure_id' => $structure->id,
                            'discipline_id' => $request->discipline_id,
                            'categorie_id' => $request->categorie_id,
                            'activite_id' => $structureActivite->id,
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

        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline])->with('success', 'Activité ajoutée, ajoutez d\'autres activités à votre structure.');

    }
}
