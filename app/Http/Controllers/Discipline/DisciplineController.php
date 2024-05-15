<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisciplineRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineSimilaire;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\ListDisciplineResource;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $structuresCount = Cache::remember('structures_count', 600, function () {
            return Structure::count();
        });

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $disciplines = ListDiscipline::withProductsAndDisciplinesSimilaires()
                        ->withCount('structureProduits')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structure_produits_count')
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Disciplines/Index', [
            'disciplines' => fn () => $disciplines,
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'structuresCount' => fn () => $structuresCount,
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
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

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

        $structures = Structure::withRelations()
            ->whereHas('activites', function ($subquery) use ($discipline) {
                $subquery->where('discipline_id', $discipline->id);
            })
            ->paginate(12);

        $produits = $discipline->structureProduits()
                    ->withRelations()
                    ->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $discipline->timestamps = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'discipline' => fn () => $discipline,
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'produits' => fn () => $produits,
            'structures' => fn () => $structures,
            'posts' => fn () => PostResource::collection($posts),
        ]);
    }
    /**
    * Update the specified resource in storage.
    */
    public function create(StoreDisciplineRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validated = $request->validated();

        $slug = Str::slug($validated['name'], '-');

        $discipline = ListDiscipline::create([
            "name" => $validated['name'],
            "slug" => $slug,
            "description" => $validated['description'],
            'theme' => $validated['theme'],
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
            'discipline' => fn () => $discipline,
        ]);
    }
    /**
    * Update the specified resource in storage.
    */
    public function update(StoreDisciplineRequest $request, ListDiscipline $discipline): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validated = $request->validated();

        $discipline = ListDiscipline::find($discipline->id);

        $discipline->update([
            'name' => $validated['name'],
            "description" => $validated['description'],
            'theme' => $validated['theme']
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

    public function getCategoriesFromSlug($slug)
    {
        $listdiscipline = ListDiscipline::where('slug', $slug)->firstOrFail();

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
