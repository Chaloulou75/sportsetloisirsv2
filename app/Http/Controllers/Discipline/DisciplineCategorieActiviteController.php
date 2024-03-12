<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DisciplineCategorieActiviteController extends Controller
{
    public function show(ListDiscipline $discipline, $category, StructureActivite $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::withRelations()->find(request()->produit);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                ->where('discipline_id', $requestDiscipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->withCount('structures_produits')
                ->orderByDesc('structures_produits_count')
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline) {
                $subquery->where('discipline_id', $requestDiscipline->id);
            });
        })
        ->select(['id', 'name', 'slug'])
        ->get();

        $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)
        ->where('slug', $category)
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->first();

        $activite = StructureActivite::with([
            'structure',
            'structure.adresses',
            'instructeurs'
        ])->find($activite->id);
        $produits = $activite->produits()->withRelations()->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
        ->where('discipline_id', $discipline->id)
        ->where('categorie_id', $requestCategory->id)
        ->where('visible_front', true)
        ->get();

        $activiteSimilaires = StructureActivite::with([
                'produits',
                'produits.criteres',
                'produits.criteres.sous_criteres',
                'produits.adresse'
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
            'discipline' => fn () => $requestDiscipline,
            'familles' => fn () => $familles,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'activite' => fn () => $activite,
            'criteres' => fn () => $criteres,
            'activiteSimilaires' => fn () => $activiteSimilaires,
            'selectedProduit' => fn () => $selectedProduit,
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
            'requestCategory' => fn () => $requestCategory,
            'produits' => fn () => $produits,
        ]);
    }
}
