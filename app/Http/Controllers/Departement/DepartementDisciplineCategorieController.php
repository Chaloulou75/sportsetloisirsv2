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
use App\Models\LienDisciplineCategorieCritere;

class DepartementDisciplineCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Departement $departement, ListDiscipline $discipline, $category): Response
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

        $discipline = ListDiscipline::withProductsAndDisciplinesSimilaires()
        ->find($discipline->id);

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('slug', $category)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id)
                ->where('visible_front', true)
                ->get();

        $departement = Departement::withCitiesAndRelations()
                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                        ->find($departement->id);

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function ($query) use ($departement) {
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

        $produits = $departement->cities->flatMap(function ($city) use ($discipline, $category) {
            return $city->produits()->withRelations()->where('discipline_id', $discipline->id)->where('categorie_id', $category->id)->get();
        })->paginate(12);

        $structuresFlat = $departement->cities->flatMap(function ($city) use ($discipline, $category) {
            return $city->structures->filter(function ($structure) use ($discipline, $category) {
                return $structure->produits->where('discipline_id', $discipline->id)->where('categorie_id', $category->id)->isNotEmpty();
            });
        });

        $structures = $structuresFlat->each(function ($structure) use ($discipline, $category) {
            $structure->load([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,ville,ville_formatee,code_postal',
                'structuretype:id,name,slug',
                'disciplines' => function ($query) use ($discipline) {
                    $query->where('id', $discipline->id);
                },
                'disciplines.discipline:id,name,slug',
                'categories' => function ($query) use ($category) {
                    $query->where('id', $category->id);
                },
                'activites' => function ($query) use ($discipline, $category) {
                    $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->whereHas('activites', function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
            })->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])->get();
        })->paginate(12);

        $citiesAround = $departement->cities()->whereHas('produits')
                            ->select('id', 'slug', 'ville')
                            ->limit(10)
                            ->get();

        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Disciplines/Categories/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'category' => fn () => $category,
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
            'criteres' => fn () => $criteres,
            'departement' => fn () => $departement,
            'citiesAround' => fn () => $citiesAround,
            'produits' => fn () => $produits,
            'structures' => fn () => $structures,
            'discipline' => fn () => $discipline,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => CityResource::collection($allCities),
            'posts' => fn () => PostResource::collection($posts),
        ]);

    }

}
