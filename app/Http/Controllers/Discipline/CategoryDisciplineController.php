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
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\LienDisCatTariftype;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Http\Resources\StructureResource;
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\DisCatTarifTypeResource;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\LienDisCatTariftypeResource;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class CategoryDisciplineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, ListDiscipline $discipline, $category): Response
    {
        $filters = $request->only(['crit', 'ssCrit']);
        $page = $request->input('page', 1);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $discipline = ListDiscipline::withProductsAndDisciplinesSimilaires()
        ->find($discipline->id);

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('slug', $category)->firstOrFail();

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
            ->where('discipline_id', $discipline->id)
            ->withCount('structures_produits')
            ->orderByDesc('structures_produits_count')
            ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline) {
            $query->whereHas('produits', function ($subquery) use ($discipline) {
                $subquery->where('discipline_id', $discipline->id);
            });
        })->select(['id', 'name', 'slug'])
        ->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
        ->where('discipline_id', $discipline->id)
        ->where('categorie_id', $category->id)
        ->where('visible_front', true)
        ->get();

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $structures = Structure::with([
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'structuretype',
                    'activites' => function ($query) use ($discipline, $category) {
                        $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
                    },
                    'activites.discipline',
                    'activites.categorie',
                ])
                ->whereHas('activites', function ($subquery) use ($discipline, $category) {
                    $subquery->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
                })
                ->paginate(4, ['*'], 'strpage')
                ->withQueryString();

        $produits = $discipline->structureProduits()
                                ->withRelations()
                                ->where('categorie_id', $category->id)
                                ->filter($filters)
                                ->paginate(4, ['*'], 'prodpage', $page);

        $discipline->timestamp = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Categories/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'category' => fn () => LienDisciplineCategorieResource::make($category),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories),
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'structures' => fn () => StructureResource::collection($structures),
            'posts' => fn () => PostResource::collection($posts),
            'filters' => fn () => $filters ?? null,
        ]);
    }

    public function store(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorieNotInId = $request->input('categorieNotIn');
        $categorieNotIn = Categorie::findOrFail($categorieNotInId);
        $discipline->categories()->attach($categorieNotIn, [
            'slug' => Str::slug($categorieNotIn->nom),
            'nom_categorie_client' => $categorieNotIn->nom,
            'nom_categorie_pro' => $categorieNotIn->nom,
        ]);

        $attached = $discipline->categories()->withPivot('categorie_id')->wherePivot('categorie_id', $categorieNotIn->id)->first();

        if ($attached) {
            $newSlug = Str::slug($categorieNotIn->nom) . '-' . $attached->pivot->id;
            $discipline->categories()->updateExistingPivot($categorieNotIn->id, ['slug' => $newSlug]);
        }

        return to_route('admin.disciplines.categories.edit', $discipline)->with('success', 'Catégorie ajoutée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        $categories = LienDisciplineCategorie::with([
                'discipline',
                'categorie',
                'criteres',
                'criteres.type_champ',
                'criteres.critere',
                'criteres.valeurs',
                'criteres.valeurs.sous_criteres',
                'criteres.valeurs.sous_criteres.type_champ',
                'criteres.valeurs.sous_criteres.sous_criteres_valeurs',
            ])
            ->where('discipline_id', $discipline->id)
            ->get();

        $categoriesIds = $categories->pluck('categorie_id');

        $otherCategories = Categorie::select('id', 'nom')->whereNotIn('id', $categoriesIds)->get();

        return Inertia::render('Admin/Disciplines/Categories/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categories' => $categories,
            'otherCategories' => $otherCategories,
            'discipline' => $discipline,
        ]);

    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorieInId = $request->input('categorieIn');
        $categorieIn = Categorie::findOrFail($categorieInId);
        $disciplineCategorie = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('categorie_id', $categorieIn->id)->first();
        $discCatCrit = LienDisciplineCategorieCritere::with('valeurs')->where('categorie_id', $disciplineCategorie->id)->get();

        if($discCatCrit) {
            foreach ($discCatCrit as $item) {
                if ($item->valeurs->isNotEmpty()) {
                    foreach ($item->valeurs as $valeur) {
                        $valeur->delete();
                    }
                }
            }
        }

        if($discCatCrit->isNotEmpty()) {
            foreach($discCatCrit as $critere) {
                $critere->delete();
            }
        }

        $discipline->categories()->detach($categorieIn);

        return to_route('admin.disciplines.categories.edit', $discipline)->with('success', 'Catégorie supprimée, ainsi que les critères et valeurs associées à cette catégorie.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline)
    {
        $request->validate([
            'nom_categorie_client' => ['required', 'string', 'max:255'],
            'nom_categorie_pro' => ['required', 'string', 'max:255'],
            'id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
        ]);

        $categorieDiscipline = LienDisciplineCategorie::findOrFail($request->id);

        $categorieDiscipline->update([
            'slug' => Str::slug($request->nom_categorie_client) . '-' . $categorieDiscipline->id,
            'nom_categorie_client' => $request->nom_categorie_client,
            'nom_categorie_pro' => $request->nom_categorie_pro,
        ]);

        return to_route('admin.disciplines.categories.edit', $discipline)->with('success', 'Catégorie mise à jour');
    }

    public function getTarifs(ListDiscipline $discipline, Categorie $categorie)
    {
        $disCat = LienDisciplineCategorie::with('tarif_types')->where('categorie_id', $categorie->id)->where('discipline_id', $discipline->id)->firstOrFail();

        $tarifs = $disCat->tarif_types;
        return LienDisCatTariftypeResource::collection($tarifs);
    }
}
