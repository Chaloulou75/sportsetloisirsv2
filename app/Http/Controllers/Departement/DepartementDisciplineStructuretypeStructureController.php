<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Http\Resources\StructureResource;
use App\Http\Resources\DepartementResource;
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class DepartementDisciplineStructuretypeStructureController extends Controller
{
    /**
         * Display the specified resource.
         */
    public function show(Request $request, Departement $departement, ListDiscipline $discipline, Structuretype $structuretype, Structure $structure): Response
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
        $departement = Departement::withCitiesAndRelations()
                ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                ->find($departement->id);

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()
        ->find($discipline->id);

        $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype->id);


        $structure = Structure::with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'departement',
                    'structuretype',
                    'disciplines',
                    'disciplines.str_categories' => function ($query) {
                        $query->withCount('str_activites');
                    },
                    'disciplines.str_categories.str_activites' => function ($query) use ($filters) {
                        $query->whereHas('produits', function ($subQuery) use ($filters) {
                            $subQuery->filter($filters);
                        });
                    },
                    'disciplines.str_categories.str_activites.discipline',
                    'disciplines.str_categories.str_activites.produits' => function ($query) use ($filters) {
                        $query->filter($filters);
                    },
                    'disciplines.str_categories.str_activites.produits.adresse',
                    'disciplines.str_categories.str_activites.produits.criteres',
                    'disciplines.str_categories.str_activites.produits.criteres.critere',
                    'disciplines.str_categories.str_activites.produits.criteres.critere_valeur',
                    'disciplines.str_categories.str_activites.produits.criteres.critere_valeur.sous_criteres',
                    'disciplines.str_categories.str_activites.produits.criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs.sous_critere_valeur',
                    'disciplines.str_categories.str_activites.produits.criteres.sous_criteres',
                    'disciplines.str_categories.str_activites.produits.criteres.sous_criteres.sous_critere',
                    'disciplines.str_categories.str_activites.produits.criteres.sous_criteres.sous_critere_valeur',
                    'disciplines.str_categories.str_activites.produits.plannings',
                ])->withCount([
                    'disciplines',
        ])->find($structure->id);


        $categories = LienDisciplineCategorie::whereHas('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
                        ->where('discipline_id', $requestDiscipline->id)
                        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline, $departement) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline, $departement) {
                $subquery->where('discipline_id', $requestDiscipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($departement) {
                            $addressQuery->whereIn('city_id', $departement->cities->pluck('id'));
                        });
            });
        })
                        ->select(['id', 'name', 'slug'])
                        ->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
            ->whereIn('discipline_id', $structure->activites->pluck('discipline_id'))
            ->whereIn('categorie_id', $structure->activites->pluck('categorie_id'))
            ->where('visible_front', true)
            ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        $currentRoute = [
                'name' => 'departements.disciplines.structuretypes.structures.show',
                'params' => [
                    'departement' => $departement,
                    'discipline' => $discipline,
                    'structuretype' => $structuretype,
                    'structure' => $structure,
                ]
            ];


        return Inertia::render('Structures/Show', [
            'structure' => fn () => StructureResource::make($structure),
            'familles' => fn () => FamilleResource::collection($familles),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories) ,
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'departement' => fn () => DepartementResource::make($departement),
            'requestDiscipline' => fn () => ListDisciplineResource::make($requestDiscipline),
            'structuretypeElected' => fn () => StructuretypeResource::make($structuretypeElected),
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'filters' => $filters ?? null,
            'currentRoute' => $currentRoute,
        ]);
    }
}
