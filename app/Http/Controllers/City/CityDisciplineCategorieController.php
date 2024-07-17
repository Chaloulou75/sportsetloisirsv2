<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
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
use App\Http\Resources\StructureProduitResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class CityDisciplineCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, City $city, ListDiscipline $discipline, $category): Response
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

        $discipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)
        ->where('slug', $category)
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->first();

        $city = City::with(['structures', 'produits.adresse'])
                            ->withProductsAndDepartement()
                            ->withCount('produits')
                            ->withCount('structures')
                            ->find($city->id);

        $citiesAround = City::with('produits')->withCitiesAround($city)->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city, $cityAroundIds) {
            $query->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
        })
                ->where('discipline_id', $discipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $category, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $category, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $discipline->id)
                        ->where('categorie_id', $category->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
            });
        })
                ->select(['id', 'name', 'slug'])
                ->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id)
                ->where('visible_front', true)
                ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) use ($discipline, $category, $filters) {
            return $city->produits()
                ->withRelations()
                ->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id)
                ->filter($filters)
                ->get();
        });

        $produitsFromCity = $city->produits()
                ->withRelations()
                ->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id)
                ->filter($filters)
                ->get();


        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(4, null, 'prodpage', $page);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) use ($discipline, $category) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },

                'structuretype',
                'activites' => function ($query) use ($discipline, $category) {
                    $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
                },
                'activites.discipline',
                'activites.categorie',
            ])->whereHas('activites', function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
            })->get();
        });

        $structuresFromCity = $city->structures()->with([
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
        ->whereHas('activites', function ($query) use ($discipline, $category) {
            $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
        })->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(4, null, 'strpage');

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Categories/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'category' => fn () => LienDisciplineCategorieResource::make($category),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories),
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'structures' => fn () => StructureResource::collection($structures),
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'allCities' => fn () => CityResource::collection($allCities),
            'posts' => fn () => PostResource::collection($posts),
            'filters' => fn () => $filters ?? null,
        ]);
    }
}
