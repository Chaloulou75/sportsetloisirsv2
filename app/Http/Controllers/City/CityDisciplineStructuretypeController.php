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
use App\Models\StructureTypeInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\LienDisciplineCategorie;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityDisciplineStructuretypeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, $discipline, $structuretype): Response
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

        $discipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->where('slug', $discipline)
        ->first();

        $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();


        $city = City::with(['structures', 'produits.adresse'])
                            ->withProductsAndDepartement()
                            ->where('slug', $city->slug)
                            ->withCount('produits')
                            ->withCount('structures')
                            ->first();


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

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $discipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
            });
        })->select(['id', 'name', 'slug'])
                ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) use ($discipline, $structuretypeElected) {
            return $city->produits()->withRelations()->where('discipline_id', $discipline->id)
            ->whereHas('structure', function ($query) use ($structuretypeElected) {
                $query->where('structuretype_id', $structuretypeElected->id);
            })->get();
        });

        $produitsFromCity = $city->produits()->withRelations()->where('discipline_id', $discipline->id)
        ->whereHas('structure', function ($query) use ($structuretypeElected) {
            $query->where('structuretype_id', $structuretypeElected->id);
        })
        ->get();

        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) use ($discipline, $structuretypeElected) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,ville_formatee,code_postal',
                'structuretype:id,name,slug',
                'activites' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->whereHas('activites', function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            })
            ->where('structuretype_id', $structuretypeElected->id)
            ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
            ->get();
        });

        $structuresFromCity = $city->structures()->with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,slug,ville,ville_formatee,code_postal',
            'structuretype:id,name,slug',
            'activites' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'activites.discipline:id,name,slug',
            'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])
        ->where('structuretype_id', $structuretypeElected->id)
        ->whereHas('activites', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })
        ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
        ->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();


        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Structuretypes/Show', [
            'familles' => $familles,
            'structuretypeElected' => $structuretypeElected,
            'allStructureTypes' => $allStructureTypes,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'produits' => $produits,
            'structures' => $structures,
            'discipline' => $discipline,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'posts' => $posts,
        ]);

    }
}
