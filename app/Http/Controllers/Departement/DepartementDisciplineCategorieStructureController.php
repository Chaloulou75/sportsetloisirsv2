<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DepartementDisciplineCategorieStructureController extends Controller
{
    public function show(Departement $departement, ListDiscipline $discipline, $category, $structure): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $structure = Structure::withRelations()
                            ->where('slug', $structure)
                            ->first();

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $departement = Departement::withCitiesAndRelations()
                ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                ->find($departement->id);

        $categories = LienDisciplineCategorie::whereHas('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
                        ->where('discipline_id', $requestDiscipline->id)
                        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline, $departement) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline, $departement) {
                $subquery->where('discipline_id', $requestDiscipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($departement) {
                            $addressQuery->whereIn('city_id', $departement->cities->pluck('id'));
                        });
            });
        })
                        ->select(['id', 'name', 'slug'])
                        ->get();

        $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)
        ->where('slug', $category)
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->first();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                        ->whereIn('discipline_id', $structure->activites->pluck('discipline_id'))
                        ->whereIn('categorie_id', $structure->activites->pluck('categorie_id'))
                        ->where('visible_front', true)
                        ->get();


        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => fn () => $structure,
            'familles' => fn () => $familles,
            'allCities' => fn () => $allCities,
            'listDisciplines' => fn () => $listDisciplines,
            'criteres' => fn () => $criteres,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'requestCategory' => fn () => $requestCategory,
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
            'departement' => fn () => $departement,
            'requestDiscipline' => fn () => $requestDiscipline,
        ]);
    }
}
