<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\DepartementResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\ListDisciplineResource;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureResource;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $structuresCount = Cache::remember('structures_count', 600, function () {
            return Structure::count();
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

        $departements = Departement::with([
                            'structures:id,name,slug,presentation_courte,address,city,zip_code,address_lat,address_lng,departement_id'
                        ])
                        ->whereHas('structures')
                        ->select(['id', 'slug', 'departement', 'numero'])
                        ->withCount('structures')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Departements/Index', [
            'departements' => DepartementResource::collection($departements),
            'familles' => FamilleResource::collection($familles),
            'listDisciplines' => ListDisciplineResource::collection($listDisciplines),
            'allCities' => CityResource::collection($allCities),
            'structuresCount' => $structuresCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement): Response
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

        $departement = Departement::withCitiesAndRelations()
        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
        ->find($departement->id);

        $collectionProduits = $departement->cities->flatMap(function ($city) {
            return $city->produits()->withRelations()->get();
        });

        $flattenedDisciplines = $collectionProduits->pluck('discipline')->unique();

        $produits = $collectionProduits->paginate(12);

        $structures = $departement->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,code_postal',
                'structuretype:id,name,slug',
                'activites',
                'activites.discipline:id,name,slug',
                'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'city_id', 'departement_id', 'address_lat', 'address_lng'])->paginate(12);

        $posts = Post::with(['comments', 'author', 'tags', 'disciplines'])->latest()->take(6)->get();


        $citiesAround = $departement->cities()
                ->whereHas('produits')
                ->select('id', 'slug', 'ville', 'code_postal')
                ->limit(10)
                ->get();

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'flattenedDisciplines' => fn () => ListDisciplineResource::collection($flattenedDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'departement' => fn () => DepartementResource::make($departement),
            'produits' => fn () => $produits,
            'structures' => fn () => StructureResource::collection($structures),
            'posts' => fn () => PostResource::collection($posts),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
        ]);
    }

}
