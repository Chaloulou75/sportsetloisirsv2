<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $disciplinesCount = Discipline::count();
        $structuresCount = Structure::count();
        $citiesCount = City::count();

        $categories = Category::select(['id', 'name', 'slug'])->get();

        $disciplines = Discipline::has('structures')->select(['id', 'name', 'slug'])->withCount('structures')->orderByDesc('structures_count')->limit(12)->get();

        $topVilles = City::with('departement')->has('structures')->select(['id', 'ville', 'ville_formatee', 'departement', 'nom_departement'])->withCount('structures')->orderByDesc('structures_count')->limit(12)->get();

        $topDepartements = Departement::has('structures')->select(['id', 'departement', 'numero'])->withCount('structures')->with('cities')->whereIn('numero', $topVilles->pluck('departement'))->orderByDesc('structures_count')->limit(12)->get();

        $lastStructures = Structure::with('category:id,name')
                ->select(['id', 'name', 'slug', 'category_id', 'city', 'zip_code'])
                ->latest()
                ->limit(12)
                ->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'disciplines' => $disciplines,
            'categoriesCount' => $categoriesCount,
            'disciplinesCount' => $disciplinesCount,
            'structuresCount' => $structuresCount,
            'citiesCount'=> $citiesCount,
            'lastStructures' => $lastStructures,
            'topVilles' => $topVilles,
            'topDepartements' => $topDepartements,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'filters' => request()->all(['search']),
        ]);
    }
}
