<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Structuretype;
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

        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                                        ->select(['id', 'slug',  'code_postal', 'ville', 'ville_formatee'])
                                        ->get();


        $disciplines = ListDiscipline::with('structureProduits')->select(['id', 'name', 'slug'])
                        ->withCount('structureProduits')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structure_produits_count')
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Disciplines/Index', [
            'disciplines' => $disciplines,
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'structuresCount' => $structuresCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ListDiscipline $discipline)
    {
        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                                        ->select(['id', 'slug',  'code_postal', 'ville', 'ville_formatee'])
                                        ->get();

        $discipline = ListDiscipline::with('structureProduits')->where('slug', $discipline->slug)
            ->select(['id', 'name', 'slug', 'view_count'])
            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                ->where('discipline_id', $discipline->id)
                ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->withCount('structures_produits')
                ->orderByDesc('structures_produits_count')
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline) {
            $query->whereHas('produits', function ($subquery) use ($discipline) {
                $subquery->where('discipline_id', $discipline->id);
            });
        })
        ->select(['id', 'name', 'slug'])
        ->get();

        $structures = Structure::with([
                'creator:id,name',
                'users:id,name',
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,ville,ville_formatee,code_postal',
                'departement:id,departement,numero',
                'structuretype:id,name,slug',
                'disciplines' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'disciplines.discipline:id,name,slug',
                'categories',
                'activites' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
                'produits' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'produits.criteres:id,activite_id,produit_id,critere_id,valeur',
                'produits.criteres.critere:id,nom',
                'tarifs',
                'tarifs.tarifType',
                'tarifs.structureTarifTypeInfos',
            ])
            ->withCount('disciplines', 'produits', 'activites')
            ->whereHas('activites', function ($subquery) use ($discipline) {
                $subquery->where('discipline_id', $discipline->id);
            })
            ->paginate(12);

        $produits = $discipline->structureProduits()->with([
            'structure:id,name,slug,structuretype_id,address,address_lat,address_lng,zip_code,city_id,city,departement_id,website,view_count',
            'adresse',
            'discipline:id,name,slug,view_count',
            'categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            'activite:id,discipline_id,categorie_id,structure_id,titre,description,image,actif',
            'activite.discipline:id,name,slug',
            'activite.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            'criteres:id,activite_id,produit_id,critere_id,valeur',
            'criteres.critere:id,nom',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])
        ->paginate(12);

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
            'familles' => $familles,
            'discipline' => $discipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'produits' => $produits,
            'structures' => $structures,
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

        return to_route('admin.index')->with('success', 'Discipline ' . $discipline->name . ' créée.');
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
