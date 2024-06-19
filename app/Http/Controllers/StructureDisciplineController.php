<?php

namespace App\Http\Controllers;

use App\Http\Resources\LienDisciplineCategorieCritereResource;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
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
use App\Http\Resources\StructureCatTarifResource;
use App\Http\Resources\StructureResource;
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Contracts\Database\Eloquent\Builder;

class StructureDisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure): Response
    {
        $structure = Structure::with([
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
                        $query->with([
                            'discipline:id,name,slug',
                            'plannings' => function ($query) {
                                $query->endNotPassed();
                            },
                            'produits' => function ($query) {
                                $query->latest();
                            }
                        ]);
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

        $strCatTarifs = StructureCatTarif::withRelations()->with('produits')->where('structure_id', $structure->id)->get();

        $dejaUsedDisciplines = $structure->disciplines->pluck('id');

        $listDisciplines = ListDiscipline::with(['categories'])->select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Disciplines/Index', [
            'structure' => fn () => StructureResource::make($structure),
            'dejaUsedDisciplines' => fn () => $dejaUsedDisciplines,
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'strCatTarifs' => fn () => StructureCatTarifResource::collection($strCatTarifs),
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

        $discipline = ListDiscipline::with('categories')->where('slug', $discipline)->first();

        $structure = Structure::with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'disciplines' => function ($query) use ($discipline) {
                $query->select('liste_disciplines.id', 'liste_disciplines.name', 'liste_disciplines.slug', 'liste_disciplines.theme')
                    ->where('discipline_id', $discipline->id)
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
            'categories' => function ($query) use ($discipline) {
                $query->where('structures_categories.discipline_id', $discipline->id);
            },
            'activites' => function ($query) use ($discipline) {
                $query->with([
                    'discipline:id,name,slug',
                    'plannings' => function ($query) {
                        $query->endNotPassed()->orderByDateStart();
                    },
                    'produits' => function ($query) {
                        $query->latest();
                    },
                    'produits.adresse',
                    'produits.criteres',
                    'produits.criteres.critere',
                    'produits.criteres.critere_valeur',
                    'produits.criteres.critere_valeur.sous_criteres.sous_criteres_valeurs',
                    'produits.criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs.sous_critere_valeur',
                    'produits.criteres.sous_criteres',
                    'produits.criteres.sous_criteres.sous_critere_valeur',
                    'produits.cat_tarifs',
                    'produits.cat_tarifs.produits:id',
                    'produits.cat_tarifs.categorie',
                    'produits.cat_tarifs.cat_tarif_type',
                    'produits.cat_tarifs.cat_tarif_type.tarif_attributs',
                    'produits.cat_tarifs.cat_tarif_type.tarif_attributs.valeurs',
                    'produits.cat_tarifs.cat_tarif_type.tarif_attributs.sous_attributs',
                    'produits.cat_tarifs.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
                    'produits.cat_tarifs.attributs',
                    'produits.cat_tarifs.attributs.tarif_attribut',
                    'produits.cat_tarifs.attributs.tarif_attribut.valeurs',
                    'produits.cat_tarifs.attributs.sous_attributs',
                    'produits.cat_tarifs.attributs.sous_attributs.sous_attribut',
                    'produits.cat_tarifs.attributs.sous_attributs.sous_attribut_valeur',
                    'produits.plannings' => function ($query) {
                        $query->endNotPassed()->orderByDateStart();
                    },
                ])->where('discipline_id', $discipline->id)
                ->latest();
            }
        ])->findOrFail($structure->id);

        $strCatTarifs = StructureCatTarif::withRelations()
        ->with('produits')
        ->where('structure_id', $structure->id)
        ->whereHas('categorie', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })
        ->get();

        $uniqueCriteresInProducts = $structure->activites->flatMap(function ($activite) {
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

        return Inertia::render('Structures/Disciplines/Show', [
            'structure' => fn () => StructureResource::make($structure),
            'uniqueCriteresInProducts' => fn () => LienDisciplineCategorieCritereResource::collection($uniqueCriteresInProducts),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'strCatTarifs' => fn () => StructureCatTarifResource::collection($strCatTarifs),
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
