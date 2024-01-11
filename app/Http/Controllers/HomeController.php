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
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index(): Response
    {
        $familleCount = Famille::count();
        $disciplinesCount = ListDiscipline::count();
        $structuresCount = Structure::count();
        $produitsCount = StructureProduit::count();
        $citiesCount = City::count();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $disciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])
                        ->withCount('structureProduits')
                        ->orderByDesc('structure_produits_count')
                        ->take(12)
                        ->get();

        $topVilles = City::whereHas('produits')
                        ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'departement', 'nom_departement'])
                        ->withCount('produits')
                        ->orderByDesc('produits_count')
                        ->take(12)
                        ->get();

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

        $lastStructures = Structure::with('structuretype:id,name')
                ->select(['id', 'name', 'structuretype_id', 'slug', 'city', 'zip_code'])
                ->latest()
                ->take(12)
                ->get();

        $posts = Post::with('author', 'comments', 'tags')
                ->withCount('comments')
                ->latest()
                ->take(6)
                ->get();

        return Inertia::render('Welcome', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'disciplines' => $disciplines,
            'familleCount' => $familleCount,
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
