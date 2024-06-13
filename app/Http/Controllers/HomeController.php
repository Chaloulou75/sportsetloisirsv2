<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Http\Resources\CityResource;
use App\Http\Resources\DepartementResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\ListDisciplineResource;
use App\Http\Resources\StructureResource;

class HomeController extends Controller
{
    public function index(): Response
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
        $disciplinesCount = Cache::remember('list_disciplines_count', 600, function () use ($listDisciplines) {
            return $listDisciplines->count();
        });

        $structuresCount = Cache::remember('structures_count', 600, function () {
            return Structure::count();
        });

        $produitsCount = Cache::remember('produits_count', 600, function () {
            return StructureProduit::count();
        });

        $citiesCount = Cache::remember('cities_count', 600, function () use ($allCities) {
            return $allCities->count();
        });

        $disciplines = Cache::remember('disciplines', 600, function () use ($listDisciplines) {
            $sortedDisciplines = $listDisciplines->sortByDesc(function ($discipline) {
                return $discipline->structureProduits->count();
            });
            return $sortedDisciplines->take(12);
        });

        $topVilles = Cache::remember('top_villes', 600, function () {
            return City::whereHas('produits')
                ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'departement', 'nom_departement'])
                ->withCount('produits')
                ->orderByDesc('produits_count')
                ->take(12)
                ->get();
        });

        $departementCounts = $topVilles->groupBy('departement')
            ->map(function ($items) {
                return $items->count();
            });

        $numeroDepts = $topVilles->pluck('departement')->unique();
        $theDepartements = Departement::whereIn('numero', $numeroDepts)
                                ->select(['id', 'slug', 'departement', 'numero'])
                                ->take(12)
                                ->get();

        $topDepartements = $theDepartements
            ->map(function ($departement) use ($departementCounts, $topVilles) {
                $count = $departementCounts->get($departement->numero, 0);
                $departement->count = $count;

                $departement->produits_count = $topVilles->where('departement', $departement->numero)->sum('produits_count');

                return $departement;
            })
            ->sortByDesc('count')
            ->values();

        $lastStructures = Cache::remember('last_structures', 600, function () {
            return Structure::with(['activites', 'structuretype', 'adresses'])
                        ->select('id', 'name', 'slug', 'structuretype_id', 'zip_code', 'city', 'view_count', 'logo')
                        ->take(12)
                        ->latest()
                        ->get();
        });

        $posts = Cache::remember('posts', 600, function () {
            return Post::with([
                    'author:id,name',
                    'comments:id',
                    'tags:id,name',
                    'disciplines:id,name'
                ])
                    ->withCount('comments')
                    ->orderByDesc('likes')
                    ->orderByDesc('views_count')
                    ->take(6)
                    ->latest()
                    ->get();
        });

        return Inertia::render('Welcome', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'disciplines' => fn () => ListDisciplineResource::collection($disciplines),
            'disciplinesCount' => fn () => $disciplinesCount,
            'structuresCount' => fn () => $structuresCount,
            'produitsCount' => fn () => $produitsCount,
            'citiesCount' => fn () => $citiesCount,
            'lastStructures' => fn () => StructureResource::collection($lastStructures),
            'allCities' => fn () => CityResource::collection($allCities),
            'topVilles' => fn () => CityResource::collection($topVilles),
            'topDepartements' => fn () => DepartementResource::collection($topDepartements),
            'posts' => fn () => PostResource::collection($posts),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'filters' => request()->all(['search', 'localite']),
        ]);
    }
}
