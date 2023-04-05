<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select(['id', 'name', 'slug'])->get();

        $categoriesCount = Category::count();
        $disciplinesCount = Discipline::count();
        $structuresCount = Structure::count();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'categoriesCount' => $categoriesCount,
            'disciplinesCount' => $disciplinesCount,
            'structuresCount' => $structuresCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = Category::with(['disciplines' => function ($query) {
            $query->withCount('structures')->orderByDesc('structures_count');
        }])
                            ->where('slug', $category->slug)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->withCount(['disciplines'])
                            ->first();

        // dd($category);
        // $disciplinesWithStructures = $category->disciplines->pluck('structures_count', 'id');
        $totalStructures = $category->disciplines->sum('structures_count');

        $category->timestamps = false;
        $category->increment('view_count');

        return Inertia::render('Categories/Show', [
            'category'=> $category,
            // 'disciplinesWithStructures' => $disciplinesWithStructures,
            'totalStructures' => $totalStructures,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function loadCategories()
    {
        $categories = Category::select(['id', 'name'])->get();
        return CategoryResource::collection($categories);
    }
}
