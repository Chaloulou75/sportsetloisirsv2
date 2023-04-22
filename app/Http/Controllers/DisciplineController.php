<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Categorie;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Http\Resources\CategorieResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\DisciplineResource;
use App\Models\LienActiviteSimilaire;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $disciplines = Discipline::select(['id', 'name', 'slug'])
                        ->withCount('activites')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('activites_count')
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
                'activites:id,name,slug,structure_id,description,address,city,zip_code,country,address_lat,address_lng,discipline_id,nivel_id,activitetype_id,publictype_id',
                'activites.discipline'
            ])
            ->where('slug', $discipline->slug)
            ->select(['id', 'name', 'slug', 'view_count'])
            ->withCount('activites')
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
        $famille_id = request('famille_id');

        $disciplines = Discipline::where('famille_id', $famille_id)->get(['id', 'name', 'famille_id']);
        return DisciplineResource::collection($disciplines);
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

        $activiteSimilairesIds = LienActiviteSimilaire::where('activite_id', $discipline->id)->select('activite_similaire_id')->get();

        $activiteSimilaires = ListDiscipline::whereIn('id', $activiteSimilairesIds)->get();

        return DisciplineResource::collection($activiteSimilaires);

    }
}
