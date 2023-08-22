<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Support\Str;
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

        $familles = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })->select(['id', 'name', 'slug'])->get();

        $disciplines = ListDiscipline::whereHas('structures')->select(['id', 'name', 'slug'])
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
     * Display the specified resource.
     */
    public function show(ListDiscipline $discipline)
    {

        $familles = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })->select(['id', 'name', 'slug'])->get();

        $discipline = ListDiscipline::where('slug', $discipline->slug)
            ->select(['id', 'name', 'slug', 'view_count'])
            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $categories = LienDisciplineCategorie::where('discipline_id', $discipline->id)->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->get();

        $structures = $discipline->structures()->with([
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
        ])->withCount('disciplines', 'produits', 'activites')->paginate(6);

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
            'familles' => $familles,
            'discipline' => $discipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'structures' => $structures,
            'categories' => $categories,
        ]);
    }
    /**
    * Update the specified resource in storage.
    */
    public function create(Request $request)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'min:8'],
        ]);

        $slug = Str::slug($request->name, '-');


        $discipline = ListDiscipline::create([
            "name" => $request->name,
            "slug" => $slug,
            "description" => $request->description,
        ]);

        return to_route('admin.index')->with('success', 'Discipline '. $discipline->name .' créée.');
    }
    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'min:8'],
        ]);

        $updatedDiscipline = ListDiscipline::where('id', $discipline->id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $discipline = ListDiscipline::find($discipline->id);

        $slug = Str::slug($discipline->name, '-');
        $discipline->update(['slug' => $slug]);

        return to_route('admin.edit', $discipline)->with('success', 'Discipline mise à jour.');
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
