<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DepartementDisciplineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Departement $departement, ListDiscipline $discipline): Response
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

        $departement = Departement::withCitiesAndRelations()
                ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                ->find($departement->id);

        $categories = LienDisciplineCategorie::whereHas('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
                        ->where('discipline_id', $discipline->id)
                        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $departement) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $departement) {
                $subquery->where('discipline_id', $discipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($departement) {
                            $addressQuery->whereIn('city_id', $departement->cities->pluck('id'));
                        });
            });
        })
                        ->select(['id', 'name', 'slug'])
                        ->get();

        $produits = $departement->cities->flatMap(function ($city) use ($discipline) {
            return $city->produits()->withRelations()->where('discipline_id', $discipline->id)->get();
        })->paginate(12);

        $structures = $departement->structures()->with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,slug,ville,code_postal',
            'structuretype:id,name,slug',
            'activites' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'activites.discipline:id,name,slug',
            'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'city_id', 'departement_id', 'address_lat', 'address_lng'])
        ->paginate(12);

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();


        $citiesAround = $departement->cities()->whereHas('produits')
                                    ->select('id', 'slug', 'ville', 'code_postal')
                                    ->limit(10)
                                    ->get();


        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Disciplines/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
            'departement' => fn () => $departement,
            'produits' => fn () => $produits,
            'structures' => fn () => $structures,
            'discipline' => fn () => $discipline,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => CityResource::collection($allCities),
            'posts' => fn () => PostResource::collection($posts),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
        ]);

    }

}
