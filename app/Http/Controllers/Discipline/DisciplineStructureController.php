<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
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
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class DisciplineStructureController extends Controller
{
    /**
    * Display the specified resource.
    */
    public function show(Request $request, ListDiscipline $discipline, Structure $structure): Response
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

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()
            ->select(['id', 'name', 'slug', 'view_count', 'theme'])
            ->find($discipline->id);

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                ->where('discipline_id', $requestDiscipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->withCount('structures_produits')
                ->orderByDesc('structures_produits_count')
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline) {
                $subquery->where('discipline_id', $requestDiscipline->id);
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
                    'name' => 'disciplines.structures.show',
                    'params' => [
                        'discipline' => $discipline,
                        'structure' => $structure,
                    ]
                ];


        return Inertia::render('Structures/Show', [
            'structure' => fn () => StructureResource::make($structure),
            'familles' => fn () => FamilleResource::collection($familles),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories),
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'requestDiscipline' => fn () => ListDisciplineResource::make($requestDiscipline),
            'filters' => $filters ?? null,
            'currentRoute' => $currentRoute,
        ]);
    }
}
