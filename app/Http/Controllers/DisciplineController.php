<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Http\Resources\DisciplineResource;
use Illuminate\Database\Eloquent\Builder;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $disciplines = Discipline::select(['id', 'name', 'slug'])
                        ->withCount(['structures'])
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Disciplines/Index', [
            'disciplines' => $disciplines,
            'structuresCount' => $structuresCount,
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
        $discipline = Discipline::with([
                'structures',
                'structures.structuretype',
                'structures.activites:id,name,slug,structure_id,description,address,city,zip_code,country,address_lat,address_lng,discipline_id,nivel_id,activitetype_id,publictype_id',
                'structures.activites.discipline',
            ])
            ->where('slug', $discipline->slug)
            ->select(['id', 'name', 'slug', 'view_count'])
            ->withCount('structures')
            ->first();

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
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
