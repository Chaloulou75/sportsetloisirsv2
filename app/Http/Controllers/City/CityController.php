<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $structuresCount = Cache::remember('structuresCount', 600, function () {
            return Structure::count();
        });

        $produitsCount = Cache::remember('structuresCount', 600, function () {
            return StructureProduit::count();
        });

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $cities = City::withProducts()
                        ->withCount('produits')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('produits_count')
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Villes/Index', [
            'cities' => fn () => $cities,
            'familles' => fn () => $familles,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'structuresCount' => fn () => $structuresCount,
            'produitsCount' => fn () => $produitsCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city): Response
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

        $city = City::withProductsAndDepartement()
                    ->withCount('produits')
                    ->find($city->id);

        $citiesAround = City::withCitiesAround($city)->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($cityAr) {
            return $cityAr->produits()->withRelations()->get();
        });

        $produitsFromCity = $city->produits()->withRelations()->get();

        $flattenedDisciplines = $produitsFromCity->merge($citiesAroundProducts)->pluck('discipline')->unique();

        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,code_postal',
                'structuretype:id,name,slug',
                'activites',
                'activites.discipline:id,name,slug,theme',
                'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])
            ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
            ->get();
        });

        $structuresFromCity = $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,code_postal',
                'structuretype:id,name,slug',
                'activites',
                'activites.discipline:id,name,slug,theme',
                'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])
        ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
        ->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $posts = Post::with(['comments', 'author', 'tags', 'disciplines'])->latest()->take(6)->get();

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Show', [
            'familles' => fn () => $familles,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'city' => fn () => $city,
            'citiesAround' => fn () => $citiesAround,
            'produits' => fn () => $produits,
            'flattenedDisciplines' => fn () => $flattenedDisciplines,
            'structures' => fn () => $structures,
            'posts' => fn () => $posts,
            'filters' => request()->all(['discipline']),
        ]);
    }

    protected function radians($degrees)
    {
        return $degrees * pi() / 180;
    }

}
