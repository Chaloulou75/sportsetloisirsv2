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
use App\Http\Resources\CityResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\ListDisciplineResource;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureResource;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $structuresCount = Cache::remember('structures_count', 600, function () {
            return Structure::count();
        });

        $produitsCount = Cache::remember('produits_count', 600, function () {
            return StructureProduit::count();
        });

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
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
            'cities' => fn () => CityResource::collection($cities),
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
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
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
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
                'structuretype',
                'activites',
                'activites.discipline',
                'activites.categorie',
            ])->get();
        });

        $structuresFromCity = $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'structuretype',
                'activites',
                'activites.discipline',
                'activites.categorie',
        ])->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $posts = Post::with(['comments', 'author', 'tags', 'disciplines'])->latest()->take(6)->get();

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'flattenedDisciplines' => fn () =>  ListDisciplineResource::collection($flattenedDisciplines),
            'structures' => fn () => StructureResource::collection($structures),
            'posts' => fn () => PostResource::collection($posts),
            'filters' => request()->all(['discipline']),
        ]);
    }

    protected function radians($degrees)
    {
        return $degrees * pi() / 180;
    }

}
