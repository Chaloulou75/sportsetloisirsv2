<?php

namespace App\Http\Controllers\City;

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
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class CityStructureController extends Controller
{
    public function show(City $city, Structure $structure): Response
    {
        $discipline = request()->discipline;
        $departement = request()->departement;
        $category = request()->category;
        $structuretype = request()->structuretype;


        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $structure = Structure::withRelations()->find($structure->id);

        if($departement !== null) {
            $departement = Departement::with([
                            'structures',
                            'cities' => function ($query) {
                                $query->has('produits')->with(['produits', 'produits.adresse']);
                            }])
                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count', 'latitude', 'longitude'])
                        ->where('slug', $departement)
                        ->withCount('structures')
                        ->first();
        } else {
            $departement = null;
        }

        if ($city !== null) {
            $city = City::with(['structures', 'produits.adresse'])
                                ->withProductsAndDepartement()
                                ->withCount('produits')
                                ->withCount('structures')
                                ->find($city->id);

            $citiesAround = City::with('structures', 'produits', 'produits.adresse')->withCitiesAround($city)->get();

        } else {
            $citiesAround = null;
        }

        if($discipline !== null) {
            $requestDiscipline = ListDiscipline::where('slug', $discipline)->withProductsAndDisciplinesSimilaires()->first();


            $categories = $structure->activites->pluck('categorie')->where('discipline_id', $requestDiscipline->id);

            $categoriesWithoutProduit = LienDisciplineCategorie::whereNotIn('id', $categories->pluck('id'))->where('discipline_id', $requestDiscipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

            if($category !== null) {
                $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)->where('id', $category)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->first();
            } else {
                $requestCategory = null;
            }

            if($structuretype !== null) {
                $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype);
            } else {
                $structuretypeElected = null;
            }

        } else {
            $requestDiscipline = null;
            $requestCategory = null;
            $categories = null;
            $categoriesWithoutProduit = null;
            $disciplinesSimilaires = null;
            $structuretypeElected = null;
        }

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                        ->whereIn('discipline_id', $structure->activites->pluck('discipline_id'))
                        ->whereIn('categorie_id', $structure->activites->pluck('categorie_id'))
                        ->where('visible_front', true)
                        ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => fn () => $structure,
            'familles' => fn () => FamilleResource::collection($familles),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => $listDisciplines,
            'criteres' => fn () => $criteres,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'requestCategory' => fn () => $requestCategory,
            'categories' => fn () => $categories,
            'categoriesWithoutProduit' => fn () => $categoriesWithoutProduit,
            'allStructureTypes' => fn () => $allStructureTypes,
            'structuretypeElected' => fn () => $structuretypeElected,
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'departement' => fn () => $departement,
            'requestDiscipline' => fn () => $requestDiscipline,
            'disciplinesSimilaires' => fn () => $disciplinesSimilaires,
        ]);
    }
}
