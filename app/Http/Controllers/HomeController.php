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
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $familleCount = Famille::count();
        $disciplinesCount = ListDiscipline::count();
        $structuresCount = Structure::count();
        $citiesCount = City::count();

        $familles = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $disciplines = ListDiscipline::whereHas('structures')->select(['id', 'name', 'slug'])
                        ->withCount('structures')
                        ->orderByDesc('structures_count')
                        ->limit(12)
                        ->get();


        $allCities = City::whereHas('structures')
                                ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                                ->get();


        $topVilles = City::whereHas('structures')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee', 'departement', 'nom_departement'])
                        ->withCount('structures')
                        ->orderByDesc('structures_count')
                        ->limit(12)
                        ->get();

        $topDepartements = Departement::whereHas('structures')
                                ->select(['id', 'departement', 'numero'])
                                ->withCount('structures')
                                ->orderByDesc('structures_count')
                                ->limit(12)
                                ->get();

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
