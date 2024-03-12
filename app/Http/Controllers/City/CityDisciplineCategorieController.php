<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityDisciplineCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, ListDiscipline $discipline, $category): Response
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

        $discipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)
        ->where('slug', $category)
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->first();


        $city = City::with(['structures', 'produits.adresse'])
                            ->withProductsAndDepartement()
                            ->withCount('produits')
                            ->withCount('structures')
                            ->find($city->id);

        $citiesAround = City::with('produits')->withCitiesAround($city)->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city, $cityAroundIds) {
            $query->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
        })
                ->where('discipline_id', $discipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $category, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $category, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $discipline->id)
                        ->where('categorie_id', $category->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
            });
        })
                ->select(['id', 'name', 'slug'])
                ->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id)
                ->where('visible_front', true)
                ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) use ($discipline, $category) {
            return $city->produits()->withRelations()->where('discipline_id', $discipline->id)->where('categorie_id', $category->id)->get();
        });

        $produitsFromCity = $city->produits()->withRelations()->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id)
                ->get();


        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) use ($discipline, $category) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,ville_formatee,code_postal',
                'structuretype:id,name,slug',
                'activites' => function ($query) use ($discipline, $category) {
                    $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->whereHas('activites', function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
            })
            ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
            ->get();
        });

        $structuresFromCity = $city->structures()->with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,slug,ville,ville_formatee,code_postal',
            'structuretype:id,name,slug',
            'activites' => function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
            },
            'activites.discipline:id,name,slug',
            'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])
        ->whereHas('activites', function ($query) use ($discipline, $category) {
            $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
        })
        ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
        ->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Categories/Show', [
            'familles' => fn () => $familles,
            'category' => fn () => $category,
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
            'city' => fn () => $city,
            'citiesAround' => fn () => $citiesAround,
            'produits' => fn () => $produits,
            'structures' => fn () => $structures,
            'discipline' => fn () => $discipline,
            'criteres' => fn () => $criteres,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'posts' => fn () => $posts
        ]);

    }
}
