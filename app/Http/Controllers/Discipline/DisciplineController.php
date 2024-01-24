<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
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
    public function index(): Response
    {
        $structuresCount = Structure::count();

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $disciplines = ListDiscipline::withProductsAndDisciplinesSimilaires()
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
    public function show(ListDiscipline $discipline): Response
    {

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $discipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                ->where('discipline_id', $discipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
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
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,ville_formatee,code_postal',
                'structuretype:id,name,slug',
                'activites' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])
            ->whereHas('activites', function ($subquery) use ($discipline) {
                $subquery->where('discipline_id', $discipline->id);
            })
            ->paginate(12);

        // filtrer par "visible_block true
        $produits = $discipline->structureProduits()
                    ->withRelations()
                    ->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
            'familles' => $familles,
            'discipline' => $discipline,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'produits' => $produits,
            'structures' => $structures,
            'posts' => $posts,
        ]);
    }
    /**
    * Update the specified resource in storage.
    */
    public function create(Request $request): RedirectResponse
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
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        return Inertia::render('Admin/Disciplines/Informations/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'discipline' => $discipline,
        ]);
    }
    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, ListDiscipline $discipline): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'min:8'],
            'theme' => ['required'],
        ]);

        $discipline = ListDiscipline::find($discipline->id);

        $discipline->update([
            'name' => $request->name,
            'description' => $request->description,
            'theme' => $request->theme,
        ]);

        $slug = Str::slug($discipline->name, '-');
        $discipline->update(['slug' => $slug]);

        return to_route('admin.disciplines.informations.edit', $discipline)->with('success', 'Discipline mise à jour.');
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
