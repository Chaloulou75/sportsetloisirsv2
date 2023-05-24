<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisciplineSimilaire;
use App\Http\Resources\CategorieResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\ListDisciplineResource;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])
                        // ->withCount('activites')
                        ->filter(
                            request(['search'])
                        )
                        // ->orderByDesc('activites_count')
                        ->paginate(12)
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
    public function show(ListDiscipline $discipline)
    {
        $discipline = ListDiscipline::where('slug', $discipline->slug)
            ->select(['id', 'name', 'slug', 'view_count'])
            // ->withCount('activites')
            ->first();

        $structures = $discipline->structures->load('structuretype')->unique();

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
            'discipline'=> $discipline,
            'structures' => $structures
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline)
    {
        //
    }

    public function loadDisciplines()
    {
        $famille_id = request('famille_id');

        $disciplines = ListDiscipline::whereHas('familles', function ($query) use ($famille_id) {
            $query->where('famille_id', $famille_id);
        })->get(['id', 'name']);

        return ListDisciplineResource::collection($disciplines);
    }

    public function getCategories($id)
    {
        $listdiscipline = ListDiscipline::findOrFail($id);

        $categories = $listdiscipline->categories;

        return CategorieResource::collection($categories);

    }

    public function getDisciplinesSimilaires($id)
    {
        $discipline = ListDiscipline::findOrFail($id);

        $activiteSimilairesIds = LienDisciplineSimilaire::where('discipline_id', $discipline->id)->select('discipline_similaire_id')->get();

        $activiteSimilaires = ListDiscipline::whereIn('id', $activiteSimilairesIds)->get();

        return ListDisciplineResource::collection($activiteSimilaires);

    }
}
