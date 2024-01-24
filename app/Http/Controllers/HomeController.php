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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index(): Response
    {
        $disciplinesCount = Cache::remember('disciplinesCount', 600, function () {
            return ListDiscipline::count();
        });
        $structuresCount = Cache::remember('structuresCount', 600, function () {
            return Structure::count();
        });
        $produitsCount = Cache::remember('produitsCount', 600, function () {
            return StructureProduit::count();
        });
        $citiesCount = Cache::remember('citiesCount', 600, function () {
            return City::count();
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

        $disciplines = Cache::remember('disciplines', 600, function () {
            return ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug', 'theme'])
                ->withCount('structureProduits')
                ->orderByDesc('structure_produits_count')
                ->take(12)
                ->get();
        });

        $topVilles = Cache::remember('topVilles', 600, function () {
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

        $lastStructures = Cache::remember('lastStructures', 600, function () {
            return Structure::withRelations()
                        ->latest()
                        ->take(12)
                        ->get();
        });

        $posts = Cache::remember('posts', 600, function () {
            return Post::with('author', 'comments', 'tags', 'disciplines')
                        ->withCount('comments')
                        ->orderByDesc('likes')
                        ->orderByDesc('views_count')
                        ->latest()
                        ->take(6)
                        ->get();
        });

        return Inertia::render('Welcome', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'disciplines' => $disciplines,
            'disciplinesCount' => $disciplinesCount,
            'structuresCount' => $structuresCount,
            'produitsCount' => $produitsCount,
            'citiesCount' => $citiesCount,
            'lastStructures' => $lastStructures,
            'allCities' => $allCities,
            'topVilles' => $topVilles,
            'topDepartements' => $topDepartements,
            'posts' => $posts,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'filters' => request()->all(['search', 'localite']),
        ]);
    }
}
