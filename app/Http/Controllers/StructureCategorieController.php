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

        $discipline = ListDiscipline::with('categories')->findOrFail($discipline->id);
        $categorie = LienDisciplineCategorie::where('slug', $categorie)->first();

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
            'disciplines.str_categories' => function ($query) use ($categorie) {
                $query->with([
                            'tarif_types',
                            'tarif_types.tarif_attributs.sous_attributs.valeurs',
                            'tarif_types.tarif_attributs.valeurs'
                        ])
                        ->withCount('str_activites')
                        ->where('structures_categories.categorie_id', $categorie->id)
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
            'activites' => function ($query) use ($discipline, $categorie) {
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
                    'produits.catTarifs',
                    'produits.catTarifs.produits:id',
                    'produits.catTarifs.categorie',
                    'produits.catTarifs.cat_tarif_type',
                    'produits.catTarifs.cat_tarif_type.tarif_attributs',
                    'produits.catTarifs.cat_tarif_type.tarif_attributs.valeurs',
                    'produits.catTarifs.cat_tarif_type.tarif_attributs.sous_attributs',
                    'produits.catTarifs.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
                    'produits.catTarifs.attributs',
                    'produits.catTarifs.attributs.tarif_attribut',
                    'produits.catTarifs.attributs.tarif_attribut.valeurs',
                    'produits.catTarifs.attributs.sous_attributs',
                    'produits.catTarifs.attributs.sous_attributs.sous_attribut',
                    'produits.catTarifs.attributs.sous_attributs.sous_attribut_valeur',
                    'produits.plannings' => function ($query) {
                        $query->endNotPassed()->orderByDateStart();
                    },
                ])->where('discipline_id', $discipline->id)
                ->where('categorie_id', $categorie->id)
                ->latest();
            }
        ])->findOrFail($structure->id);

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

        $strCatTarifs = StructureCatTarif::withRelations()
                ->with('produits')
                ->where('structure_id', $structure->id)
                ->where('categorie_id', $categorie->id)
                ->get();


        return Inertia::render('Structures/Categories/Show', [
            'structure' => fn () => $structure,
            'uniqueCriteresInProducts' => fn () => $uniqueCriteresInProducts,
            'criteres' => fn () => $criteres,
            'discipline' => fn () => $discipline,
            'categorie' => fn () => $categorie,
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
