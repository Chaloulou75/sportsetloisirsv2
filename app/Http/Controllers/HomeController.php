<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $disciplinesCount = Discipline::count();
        $structuresCount = Structure::count();

        $categories = Category::select(['id', 'name', 'slug'])->get();
        $disciplines = Discipline::inRandomOrder()->limit(12)->select(['id', 'name', 'slug'])->get();

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
            'lastStructures' => $lastStructures,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
