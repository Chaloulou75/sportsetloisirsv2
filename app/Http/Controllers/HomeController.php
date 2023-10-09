<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $familleCount = Famille::count();
        $disciplinesCount = ListDiscipline::count();
        $structuresCount = Structure::count();
        $produitsCount = StructureProduit::count();
        $citiesCount = City::count();

        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $disciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])
                        ->withCount('structureProduits')
                        ->orderByDesc('structure_produits_count')
                        ->limit(12)
                        ->get();

        $allCities = City::whereHas('produits')
                                ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee'])
                                ->get();


        $topVilles = City::whereHas('produits')
                        ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'departement', 'nom_departement'])
                        ->withCount('produits')
                        ->orderByDesc('produits_count')
                        ->limit(12)
                        ->get();

        $departementCounts = $topVilles->groupBy('departement')
            ->map(function ($items) {
                return $items->count();
            });

        $numeroDepts = $topVilles->pluck('departement')->unique();
        $theDepartements = Departement::whereIn('numero', $numeroDepts)
                                ->select(['id', 'slug', 'departement', 'numero'])
                                ->limit(12)
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
                ->limit(12)
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
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'filters' => request()->all(['search', 'localite']),
        ]);
    }
}
