<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use Illuminate\Support\Facades\Auth;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class CityDisciplineStructuretypeStructureController extends Controller
{
    /**
      * Display the specified resource.
      */
    public function show(City $city, $discipline, $structuretype, $structure)
    {
        $departement = request()->departement;
        $category = request()->category;


        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();


        $structure = Structure::with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    'disciplines',
                    'disciplines.discipline:id,name,slug',
                    'categories',
                    'activites',
                    'activites.discipline:id,name',
                    'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client,nom_categorie_pro',
                    'activites.produits',
                    'activites.produits.adresse',
                    'activites.produits.criteres',
                    'activites.produits.criteres.critere',
                    'activites.produits.tarifs',
                    'activites.produits.tarifs.tarifType',
                    'activites.produits.tarifs.structureTarifTypeInfos',
                    'activites.produits.plannings',
                    ])
                    ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
                    ->where('slug', $structure)
                    ->first();

        $logoUrl = asset($structure->logo);

        if($departement !== null) {
            $departement = Departement::with([
                        'structures',
                        'cities' => function ($query) {
                            $query->has('produits')->with(['produits', 'produits.adresse']);
                        }])
                                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count', 'latitude', 'longitude'])
                                        ->where('id', $departement)
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
                                ->where('id', '!=', $city->id)
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
            ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

            if($category !== null) {
                $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)->where('id', $category)->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();
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

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
        ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
        ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => $structure,
            'familles' => $familles,
            'allCities' => $allCities,
            'listDisciplines' => $listDisciplines,
            'criteres' => $criteres,
            'logoUrl' => $logoUrl,
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
