<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisciplineCategorie;
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

        $familles = Famille::select(['id', 'name', 'slug'])->get();

        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])
                        ->withCount('structures')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Disciplines/Index', [
            'disciplines' => $disciplines,
            'familles' => $familles,
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
            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires;

        $categories = LienDisciplineCategorie::where('discipline_id', $discipline->id)->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->get();

        $structures = $discipline->structures->load([
            'famille:id,name',
            'creator:id,name',
            'users:id,name',
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines',
            'categories',
            'activites',
            'activites.discipline',
            'activites.categorie',
            'produits',
            'produits.criteres',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])
        ->map(function ($structure) {
            $disciplinesCount = $structure->disciplines->count();
            $activitiesCount = $structure->activites->count();
            $produitsCount = $structure->produits->count();

            return [
                'id' => $structure->id,
                'name' => $structure->name,
                'slug' => $structure->slug,
                'website' => $structure->website,
                'email' => $structure->email,
                'facebook' => $structure->facebook,
                'instagram' => $structure->instagram,
                'youtube' => $structure->youtube,
                'tiktok' => $structure->tiktok,
                'phone1' => $structure->phone1,
                'phone2' => $structure->phone2,
                'address' => $structure->address,
                'zip_code' => $structure->zip_code,
                'city' => $structure->city,
                'country' => $structure->country,
                'address_lat' => $structure->address_lat,
                'address_lng' => $structure->address_lng,
                'presentation_courte' => $structure->presentation_courte,
                'presentation_longue' => $structure->presentation_longue,
                'structuretype' => $structure->structuretype,
                'departement_id' => $structure->departement_id,
                'user' => $structure->creator,
                'logo' => $structure->logo ? asset($structure->logo) : 'https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
                'categories' => $structure->categories,
                'disciplines' => $structure->disciplines,
                'activites' => $structure->activites,
                'produits' => $structure->produits,
                'tarifs' => $structure->tarifs,
                'disciplines_count' => $disciplinesCount,
                'activites_count' => $activitiesCount,
                'produits_count' => $produitsCount,
            ];
        })->unique();

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
            'discipline'=> $discipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'structures'=> $structures,
            'categories' => $categories,
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
