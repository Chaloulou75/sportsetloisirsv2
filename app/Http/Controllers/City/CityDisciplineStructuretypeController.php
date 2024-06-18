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
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureResource;

class CityDisciplineStructuretypeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, ListDiscipline $discipline, StructureType $structuretype): Response
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

        $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype->id);


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

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $discipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
            });
        })->select(['id', 'name', 'slug'])
                ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) use ($discipline, $structuretypeElected) {
            return $city->produits()->withRelations()->where('discipline_id', $discipline->id)
            ->whereHas('structure', function ($query) use ($structuretypeElected) {
                $query->where('structuretype_id', $structuretypeElected->id);
            })->get();
        });

        $produitsFromCity = $city->produits()->withRelations()->where('discipline_id', $discipline->id)
        ->whereHas('structure', function ($query) use ($structuretypeElected) {
            $query->where('structuretype_id', $structuretypeElected->id);
        })
        ->get();

        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) use ($discipline, $structuretypeElected) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'structuretype',
                'activites' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'activites.discipline',
                'activites.categorie',
            ])->whereHas('activites', function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            })
            ->where('structuretype_id', $structuretypeElected->id)
            ->get();
        });

        $structuresFromCity = $city->structures()->with([
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
        ->where('structuretype_id', $structuretypeElected->id)
        ->whereHas('activites', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Structuretypes/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'structuretypeElected' => fn () => StructuretypeResource::make($structuretypeElected),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories) ,
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'structures' => fn () => StructureResource::collection($structures),
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'posts' => fn () => PostResource::collection($posts),
        ]);

    }
}
