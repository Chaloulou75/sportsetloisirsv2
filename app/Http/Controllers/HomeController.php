<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activite;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $activitesCount = Activite::count();
        // $clubsCount = Club::count();

        $categories = Category::select(['id', 'name', 'slug'])->get();
        $activites = Activite::inRandomOrder()->limit(12)->select(['id', 'name', 'slug'])->get();

        // $lastClubs = Club::with('category:id,name')
        //         ->select(['id', 'name', 'slug', 'category_id', 'city', 'zip_code'])
        //         ->latest()
        //         ->limit(12)
        //         ->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'activites' => $activites,
            'categoriesCount' => $categoriesCount,
            'activitesCount' => $activitesCount,
            // 'clubsCount' => $clubsCount,
            // 'lastClubs' => $lastClubs,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
