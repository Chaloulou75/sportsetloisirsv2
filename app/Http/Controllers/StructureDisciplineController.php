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
use App\Models\StructureCatTarif;
use App\Models\StructurePlanning;
use App\Models\ProductReservation;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\StructureProduitCritere;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Contracts\Database\Eloquent\Builder;

class StructureDisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure): Response
    {
        // $structure = Structure::withRelations()->findOrFail($structure->id);
        $structure = Structure::with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'city',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    'disciplines' => function ($query) {
                        $query->select('liste_disciplines.id', 'liste_disciplines.name', 'liste_disciplines.slug', 'liste_disciplines.theme')
                            ->withCount('str_categories')
                            ->orderBy('str_categories_count', 'desc');
                    },
                    'disciplines.str_categories' => function ($query) {
                        $query->withCount('str_activites')
                              ->with([
                                  'tarif_types',
                                  'tarif_types.tarif_attributs.sous_attributs.valeurs',
                                  'tarif_types.tarif_attributs.valeurs'
                              ])
                              ->whereHas('str_activites');
                    },
                    'disciplines.str_categories.str_activites',
                    'disciplines.str_categories.str_activites.produits',
                    'disciplines.categories' => function ($query) {
                        $query->whereDoesntHave('disc_categories.str_categories');
                    },
                    'activites' => function ($query) {
                        $query->withRelations()->latest();
                    }
                ])->findOrFail($structure->id);

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

        // $activites = StructureActivite::withRelations()
        //             ->where('structure_id', $structure->id)
        //             ->latest()
        //             ->get();

        $strCatTarifs = StructureCatTarif::withRelations()->with('produits')->where('structure_id', $structure->id)->get();

        $dejaUsedDisciplines = $structure->disciplines->pluck('id');

        $listDisciplines = ListDiscipline::with(['categories'])->select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Disciplines/Index', [
            'structure' => fn () => $structure,
            'dejaUsedDisciplines' => fn () => $dejaUsedDisciplines,
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'strCatTarifs' => fn () => $strCatTarifs,
            'allReservationsCount' => fn () => $allReservationsCount,
            'confirmedReservationsCount' => fn () => $confirmedReservationsCount,
            'pendingReservationsCount' => fn () => $pendingReservationsCount,
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

        $discipline = ListDiscipline::where('slug', $discipline)->first();

        $structure = Structure::with([
            'creator:id,name',
            'users:id,name',
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id)
                      ->with([
                          'str_categories' => function ($query) {
                              $query->withCount('str_activites');
                          },
                          'str_categories.str_activites',
                          'categories' => function ($query) {
                              $query->whereDoesntHave('disc_categories.str_categories');
                          }
                      ]);
            },
        ])->findOrFail($structure->id);

        $categoriesListByDiscipline = LienDisciplineCategorie::with([
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs'
        ])->whereHas('str_activites', function (Builder $query) use ($structure) {
            $query->where('structure_id', $structure->id);
        })->where('discipline_id', $discipline->id)->get();

        $categoriesWithoutStructures = LienDisciplineCategorie::with([
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs'
        ])->whereDoesntHave('str_activites', function (Builder $query) use ($structure) {
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

        $activiteForTarifs = StructureActivite::with([
                'structure:id,name,slug',
                'categorie:id,nom_categorie_pro',
                'discipline:id,name',
                'produits',
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
            'structure' => fn () => $structure,
            'structureActivites' => fn () => $structureActivites,
            'uniqueCriteresInProducts' => fn () => $uniqueCriteresInProducts,
            'criteres' => fn () => $criteres,
            'discipline' => fn () => $discipline,
            'categoriesListByDiscipline' => fn () => $categoriesListByDiscipline,
            'categoriesWithoutStructures' => fn () => $categoriesWithoutStructures,
            'allCategories' => fn () => $allCategories,
            'activiteForTarifs' => fn () => $activiteForTarifs,
            'strCatTarifs' => fn () => $strCatTarifs,
            'allReservationsCount' => fn () => $allReservationsCount,
            'confirmedReservationsCount' => fn () => $confirmedReservationsCount,
            'pendingReservationsCount' => fn () => $pendingReservationsCount,
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
    public function destroy(Structure $structure, ListDiscipline $discipline): RedirectResponse
    {
        StructureCategorie::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->delete();
        StructureActivite::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->delete();
        StructureProduit::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->delete();
        StructureProduitCritere::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->delete();
        StructurePlanning::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->delete();

        $structure->disciplines()->detach($discipline->id);

        return to_route('structures.disciplines.index', $structure)->with('success', 'Discipline supprimée de votre liste.');

    }
}
