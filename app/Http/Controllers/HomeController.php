<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        // $sportsCount = Sport::count();
        // $clubsCount = Club::count();

        $categories = Category::select(['id', 'name', 'slug'])->get();
        // $sports = Sport::inRandomOrder()->limit(12)->select(['id', 'name', 'slug'])->get();

        // $lastClubs = Club::with('category:id,name')
        //         ->select(['id', 'name', 'slug', 'category_id', 'city', 'zip_code'])
        //         ->latest()
        //         ->limit(12)
        //         ->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            // 'sports' => $sports,
            'categoriesCount' => $categoriesCount,
            // 'sportsCount' => $sportsCount,
            // 'clubsCount' => $clubsCount,
            // 'lastClubs' => $lastClubs,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
