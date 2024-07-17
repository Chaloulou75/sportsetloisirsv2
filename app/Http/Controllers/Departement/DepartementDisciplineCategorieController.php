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
use App\Models\StructureProduit;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Http\Resources\StructureResource;
use App\Http\Resources\DepartementResource;
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class DepartementDisciplineCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Departement $departement, ListDiscipline $discipline, $category): Response
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

        // $produits = $departement->cities->flatMap(function ($city) use ($discipline, $category, $filters) {
        //     return $city
        //     ->produits()
        //     ->withRelations()
        //     ->where('discipline_id', $discipline->id)
        //     ->where('categorie_id', $category->id)
        //     ->filter($filters)->get();
        // })->paginate(4, null, $page, 'prodpage');

        $produits = StructureProduit::query()
                ->join('structure_adresse', 'structures_produits.lieu_id', '=', 'structure_adresse.id')
                ->join('villes_france', 'structure_adresse.city_id', '=', 'villes_france.id')
                ->where('villes_france.departement', $departement->numero)
                ->where('structures_produits.discipline_id', $discipline->id)
                ->where('structures_produits.categorie_id', $category->id)
                ->withRelations()
                ->filter($filters)
                ->paginate(4, ['*'], 'prodpage', $page);


        $structures = $departement->structures()->with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'structuretype',
            'activites' => function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)
                    ->where('categorie_id', $category->id);
            },
            'activites.discipline',
            'activites.categorie',
        ])->paginate(4, ['*'], 'strpage')
        ->withQueryString();

        $citiesAround = $departement->cities()
                            ->whereHas('produits')
                            ->select('id', 'slug', 'ville', 'code_postal')
                            ->limit(10)
                            ->get();
        $posts = Post::orderByDiscipline($discipline->id)->take(6)->get();

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Disciplines/Categories/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'category' => fn () => LienDisciplineCategorieResource::make($category),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories) ,
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'departement' => fn () => DepartementResource::make($departement),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'structures' => fn () => StructureResource::collection($structures),
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'posts' => fn () => PostResource::collection($posts),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'filters' => $filters,
        ]);

    }

}
