<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Http\Resources\DisciplineResource;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $clubsCount = Club::count();

        $disciplines = Discipline::select(['id', 'name', 'slug'])
                        // ->withCount('clubs')
                        ->filter(
                            request(['search'])
                        )
                        // ->orderByDesc('clubs_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Discipline/Index', [
            'disciplines' => $disciplines,
            // 'clubsCount' => $clubsCount,
            'filters' => request()->all(['search']),
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
    public function show(Discipline $discipline)
    {
        $discipline = Discipline::with(['structures:id,category_id,name,slug'])
                                    ->where('slug', $discipline->slug)
                                    ->select(['id', 'name', 'slug', 'view_count'])
                                    ->withCount('structures')
                                    // ->withCount('structures')
                                    ->first();

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Discipline/Show', [
            'discipline'=> $discipline,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipline $discipline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discipline $discipline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discipline $discipline)
    {
        //
    }

    public function loadDisciplines()
    {
        $category_id = request('category_id');

        $disciplines = Discipline::where('category_id', $category_id)->get(['id', 'name', 'category_id']);
        return DisciplineResource::collection($disciplines);
    }
}
