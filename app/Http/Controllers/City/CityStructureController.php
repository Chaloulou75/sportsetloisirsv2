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
use Illuminate\Support\Facades\Auth;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class CityStructureController extends Controller
{
    public function show(City $city, $structure): Response
    {
        $discipline = request()->discipline;
        $departement = request()->departement;
        $category = request()->category;
        $structuretype = request()->structuretype;


        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $structure = Structure::withRelations()
                            ->where('slug', $structure)
                            ->first();

        if($departement !== null) {
            $departement = Departement::with([
                            'structures',
                            'cities' => function ($query) {
                                $query->has('produits')->with(['produits', 'produits.adresse']);
                            }])
                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count', 'latitude', 'longitude'])
                        ->where('slug', $departement)
                        ->withCount('structures')
                        ->first();
        } else {
            $departement = null;
        }

        if ($city !== null) {
            $city = City::with([
                'structures',
                'produits',
                'produits.adresse'
            ])
            ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
            ->where('slug', $city->slug)
            ->withCount('structures')
            ->first();

            $citiesAround = City::with('structures', 'produits', 'produits.adresse')
                            ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                            ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                            ->whereNot('id', $city->id)
                            ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                            ->orderBy('distance', 'ASC')
                            ->limit(10)
                            ->get();
        } else {
            $citiesAround = null;
        }

        if($discipline !== null) {
            $requestDiscipline = ListDiscipline::where('slug', $discipline)
                                        ->select(['id', 'name', 'slug', 'view_count'])
                                        ->first();

            $disciplinesSimilaires = $requestDiscipline->disciplinesSimilaires()->select(['famille', 'name', 'slug'])->whereHas('structures')->get();

            $categories = $structure->activites->pluck('categorie')->where('discipline_id', $requestDiscipline->id);

            $categoriesWithoutProduit = LienDisciplineCategorie::whereNotIn('id', $categories->pluck('id'))->where('discipline_id', $requestDiscipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

            if($category !== null) {
                $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)->where('id', $category)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();
            } else {
                $requestCategory = null;
            }

            if($structuretype !== null) {
                $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();
            } else {
                $structuretypeElected = null;
            }

        } else {
            $requestDiscipline = null;
            $requestCategory = null;
            $categories = null;
            $categoriesWithoutProduit = null;
            $disciplinesSimilaires = null;
            $structuretypeElected = null;
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
            'structure' => $structure,
            'familles' => $familles,
            'allCities' => $allCities,
            'listDisciplines' => $listDisciplines,
            'criteres' => $criteres,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'requestCategory' => $requestCategory,
            'categories' => $categories,
            'categoriesWithoutProduit' => $categoriesWithoutProduit,
            'allStructureTypes' => $allStructureTypes,
            'structuretypeElected' => $structuretypeElected,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'departement' => $departement,
            'requestDiscipline' => $requestDiscipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
        ]);
    }
}
