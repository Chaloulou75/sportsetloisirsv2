<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Builder;
use App\Models\LienDisciplineCategorieCritere;

class StructureCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Structure $structure, ListDiscipline $discipline, $categorie): Response
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
        ])
        ->select(['id', 'name', 'slug'])
        ->where('slug', $structure->slug)
        ->first();

        $discipline = ListDiscipline::where('slug', $discipline->slug)->first();

        $categorie = LienDisciplineCategorie::with([
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs'
        ])->findOrFail($categorie);

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
            ->where('categorie_id', $categorie->id)
            ->latest()
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

        $strCatTarifs = StructureCatTarif::withRelations()
                ->with('produits')
                ->where('structure_id', $structure->id)
                ->where('categorie_id', $categorie->id)
                ->get();

        $activiteForTarifs = StructureActivite::with([
            'structure:id,name,slug',
            'categorie:id,nom_categorie_pro',
            'discipline:id,name',
            'produits',])
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

        return Inertia::render('Structures/Categories/Show', [
            'structure' => fn () => $structure,
            'structureActivites' => fn () => $structureActivites,
            'uniqueCriteresInProducts' => fn () => $uniqueCriteresInProducts,
            'criteres' => fn () => $criteres,
            'discipline' => fn () => $discipline,
            'categorie' => fn () => $categorie,
            'allCategories' => fn () => $allCategories,
            'categoriesListByDiscipline' => fn () => $categoriesListByDiscipline,
            'categoriesWithoutStructures' => fn () => $categoriesWithoutStructures,
            'strCatTarifs' => fn () => $strCatTarifs,
            'activiteForTarifs' => fn () => $activiteForTarifs,
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
    public function destroy(Structure $structure, LienDisciplineCategorie $categorie): RedirectResponse
    {
        StructureActivite::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->delete();
        StructureProduit::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->delete();
        StructureProduitCritere::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->delete();
        StructurePlanning::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->delete();

        $structure->categories()->detach($categorie->id);

        $disciplineId = $categorie->discipline_id;

        $remainingCategorie = StructureCategorie::where('structure_id', $structure->id)->where('discipline_id', $disciplineId)
                        ->exists();

        if (!$remainingCategorie) {
            $structure->disciplines()->detach($disciplineId);
        }

        return to_route('structures.disciplines.index', $structure)->with('success', 'Catégorie supprimée de votre liste.');
    }
}
