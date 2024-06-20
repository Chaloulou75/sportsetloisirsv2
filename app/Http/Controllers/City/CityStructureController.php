<?php

namespace App\Http\Controllers\City;

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
use App\Http\Resources\DepartementResource;
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

class CityStructureController extends Controller
{
    public function show(City $city, Structure $structure): Response
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

        $structure = Structure::withRelations()->find($structure->id);

        if ($city !== null) {
            $city = City::with(['structures', 'produits.adresse'])
                                ->withProductsAndDepartement()
                                ->withCount('produits')
                                ->withCount('structures')
                                ->find($city->id);

            $citiesAround = City::with('structures', 'produits', 'produits.adresse')->withCitiesAround($city)->get();

        } else {
            $citiesAround = null;
        }

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                        ->whereIn('discipline_id', $structure->activites->pluck('discipline_id'))
                        ->whereIn('categorie_id', $structure->activites->pluck('categorie_id'))
                        ->where('visible_front', true)
                        ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

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
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
        ]);
    }
}
