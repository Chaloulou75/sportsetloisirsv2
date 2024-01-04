<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\ListeTarifType;
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
use App\Models\LienDisciplineCategorieCritere;
use App\Models\StructureCatTarif;
use Illuminate\Contracts\Database\Eloquent\Builder;

class StructureDisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure): Response
    {
        $structure = Structure::with([
            'disciplines',
            'activites',
            'adresses' => function ($query) {
                $query->latest();
            },
            'produits',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'tarifs.structureTarifTypeInfos.tarifTypeAttribut'
        ])->select(['id', 'name', 'slug'])
        ->where('id', $structure->id)
        ->first();

        $tarifTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type', 'slug'])->get();

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

        $activites = StructureActivite::withRelations()
                    ->where('structure_id', $structure->id)
                    ->latest()
                    ->get();

        $actByDiscAndCategorie = $activites->groupBy('discipline.name')->map(function ($disciplineCategories) {
            $categories = $disciplineCategories->groupBy('categorie.nom_categorie_pro')->map(function ($categorieItems) {
                return [
                            'activite_id' => $categorieItems->first()->id,
                            'categorie_id' => $categorieItems->first()->categorie->id,
                            'name' => $categorieItems->first()->categorie->nom_categorie_pro ?? 'Sans Catégorie',
                            'count' => $categorieItems->count(),
                        ];
            })->sortByDesc('count');

            $categoryIdsInDiscipline = $categories->pluck('categorie_id');

            $missingCategories = LienDisciplineCategorie::where('discipline_id', $disciplineCategories->first()->discipline->id)
            ->whereNotIn('id', $categoryIdsInDiscipline)
            ->get();

            return [
                'id' => $disciplineCategories->first()->id,
                'discipline_id' => $disciplineCategories->first()->discipline->id,
                'disciplineName' => $disciplineCategories->first()->discipline->name,
                'disciplineSlug' => $disciplineCategories->first()->discipline->slug,
                'count' => $disciplineCategories->count(),
                'categories' => $categories,
                'missingCategories' => $missingCategories,
            ];
        });

        $categoriesListByDiscipline = LienDisciplineCategorie::with([
                    'tarif_types',
                    'tarif_types.tarif_attributs.sous_attributs.valeurs',
                    'tarif_types.tarif_attributs.valeurs'
                ])->whereHas('structures_activites', function (Builder $query) use ($structure) {
                    $query->where('structure_id', $structure->id);
                })->get();


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

        $strCatTarifs = StructureCatTarif::withRelations()->with('produits')->where('structure_id', $structure->id)->get();

        $categories = Categorie::with('disciplines')->select(['id', 'nom', 'ico'])->get();

        $dejaUsedDisciplines = $structure->disciplines->unique()->pluck('discipline_id');

        $listDisciplines = ListDiscipline::with(['categories'])->select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Disciplines/Index', [
            'structure' => $structure,
            'categories' => $categories,
            'dejaUsedDisciplines' => $dejaUsedDisciplines,
            'listDisciplines' => $listDisciplines,
            'activites' => $activites,
            'tarifTypes' => $tarifTypes,
            'actByDiscAndCategorie' => $actByDiscAndCategorie,
            'categoriesListByDiscipline' => $categoriesListByDiscipline,
            'activiteForTarifs' => $activiteForTarifs,
            'strCatTarifs' => $strCatTarifs,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure, $discipline): Response
    {
        if (!Gate::allows('update-structure', $structure)) {
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

        $discipline = ListDiscipline::where('slug', $discipline)->first();

        $categoriesListByDiscipline = LienDisciplineCategorie::with([
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs'
        ])->whereHas('structures_activites', function (Builder $query) use ($structure) {
            $query->where('structure_id', $structure->id);
        })->where('discipline_id', $discipline->id)->get();

        $categoriesWithoutStructures = LienDisciplineCategorie::with([
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs'
        ])->whereDoesntHave('structures_activites', function (Builder $query) use ($structure) {
            $query->where('structure_id', $structure->id);
        })->where('discipline_id', $discipline->id)->get();

        $allCategories = $categoriesListByDiscipline->merge($categoriesWithoutStructures);

        $structureActivites = $structure->activites()->withRelations()
            ->with([
                'produits.criteres.critere_valeur.sous_criteres.sous_criteres_valeurs',
                'produits.criteres.sous_criteres.sous_critere_valeur'
            ])
            ->where('discipline_id', $discipline->id)
            ->latest()
            ->get();

        $strCatTarifs = StructureCatTarif::withRelations()
        ->with('produits')
        ->where('structure_id', $structure->id)
        ->whereHas('categorie', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })
        ->get();

        $uniqueCriteresInProducts = $structureActivites->flatMap(function ($activite) {
            return $activite->produits->flatMap(function ($produit) {
                return $produit->criteres->map(function ($structureProduitCritere) {
                    return $structureProduitCritere->critere;
                });
            });
        })->keyBy('id')->values();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
            ->where('discipline_id', $discipline->id)
            ->where('visible_back', true)
            ->get();

        $tarifTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type', 'slug'])->get();

        $activiteForTarifs = StructureActivite::with([
                'structure:id,name,slug',
                'categorie:id,nom_categorie_pro',
                'discipline:id,name',
                'produits',
                'produits.tarifs',
                'produits.tarifs.structureTarifTypeInfos',
                'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut'
            ])
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
                                        'catTarifs' => $produitItem->catTarifs->map(function ($catTarifItem) {
                                            return [
                                                'id' => $catTarifItem->id,
                                                'typeId' => $catTarifItem->dis_cat_tar_typ_id,
                                                'titre' => $catTarifItem->titre,
                                                'description' => $catTarifItem->description,
                                                'amount' => $catTarifItem->amount,
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

        return Inertia::render('Structures/Disciplines/Show', [
            'structure' => $structure,
            'structureActivites' => $structureActivites,
            'uniqueCriteresInProducts' => $uniqueCriteresInProducts,
            'criteres' => $criteres,
            'discipline' => $discipline,
            'categoriesListByDiscipline' => $categoriesListByDiscipline,
            'categoriesWithoutStructures' => $categoriesWithoutStructures,
            'allCategories' => $allCategories,
            'tarifTypes' => $tarifTypes,
            'activiteForTarifs' => $activiteForTarifs,
            'strCatTarifs' => $strCatTarifs,
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
    public function destroy(Structure $structure, $discipline): RedirectResponse
    {
        $structure = Structure::where('slug', $structure->slug)->firstOrFail();
        $discipline = ListDiscipline::findOrFail($discipline);

        $structureDiscipline = StructureDiscipline::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->first();

        $categories = StructureCategorie::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $activites = StructureActivite::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $produits = StructureProduit::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $criteres = StructureProduitCritere::with('sous_criteres')->where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $plannings = StructurePlanning::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        if($produits->isNotEmpty()) {
            foreach($produits as $produit) {
                if($produit->tarifs()->count() > 0) {
                    $produit->tarifs()->detach();
                }
                $produit->delete();
            }
        }

        if($plannings->isNotEmpty()) {
            foreach($plannings as $planning) {
                $planning->delete();
            }
        }

        if($criteres->isNotEmpty()) {
            foreach($criteres as $critere) {
                if($critere->sous_criteres()) {
                    foreach($critere->sous_criteres() as $souscritere) {
                        $souscritere->delete();
                    }
                }
                $critere->delete();
            }
        }

        if($activites->isNotEmpty()) {
            foreach($activites as $activite) {
                $activite->delete();
            }
        }

        if($categories->isNotEmpty()) {
            foreach($categories as $categorie) {
                $categorie->delete();
            }
        }

        if($structureDiscipline) {
            $structureDiscipline->delete();
        }

        return to_route('structures.disciplines.index', $structure)->with('success', 'Discipline supprimée de votre liste.');

    }
}
