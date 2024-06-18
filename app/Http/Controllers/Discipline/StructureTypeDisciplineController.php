<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureResource;

class StructureTypeDisciplineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(ListDiscipline $discipline, StructureType $structuretype): Response
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

        $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype->id);

        $criteres = LienDisciplineCategorieCritere::withValeurs()->where('discipline_id', $discipline->id)->get();

        $structures = Structure::with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'structuretype',
            'activites' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'activites.discipline',
            'activites.categorie',
        ])
        ->whereHas('activites', function ($subquery) use ($discipline) {
            $subquery->where('discipline_id', $discipline->id);
        })->where('structuretype_id', $structuretypeElected->id)
        ->paginate(12);


        $produits = $discipline->structureProduits()->withRelations()->whereHas('structure', function ($query) use ($structuretypeElected) {
            $query->where('structuretype_id', $structuretypeElected->id);
        })
        ->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $discipline->timestamp = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Structuretypes/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories),
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'structures' => fn () => StructureResource::collection($structures) ,
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'allCities' => fn () => CityResource::collection($allCities),
            'posts' => fn () => PostResource::collection($posts),
            'structuretypeElected' => StructuretypeResource::make($structuretypeElected),
        ]);

    }
}
