<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Discipline;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
        // $clubsCount = Club::count();

        return Inertia::render('Category/Index', [
            'categories' => $categories,
            'categoriesCount' => $categoriesCount,
            'disciplinesCount' => $disciplinesCount,
            // 'clubsCount' => $clubsCount,
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
        $category = Category::with(['disciplines:id,category_id,name,slug'])
                            ->where('slug', $category->slug)
                            ->select(['id', 'name', 'slug'])
                            ->withCount('disciplines')
                            // ->withCount('structures')
                            ->first();

        return Inertia::render('Category/Show', [
            'category'=> $category,
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
