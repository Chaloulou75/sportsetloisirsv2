<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();
        $produitsCount = StructureProduit::count();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $cities = City::whereHas('produits')->select(['id', 'slug', 'ville', 'ville_formatee', 'code_postal'])
                        ->withCount('produits')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('produits_count')
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Villes/Index', [
            'cities' => $cities,
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'structuresCount' => $structuresCount,
            'produitsCount' => $produitsCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $city = City::with(['produits'])
                    ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                    ->where('slug', $city->slug)
                    ->withCount('produits')
                    ->first();

        $citiesAround = City::whereHas('produits')
                    ->select('id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                    ->selectRaw("
                        (6366 * acos(
                            cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) +
                            sin(radians({$city->latitude})) * sin(radians(latitude))
                        )) AS distance
                    ")
                    ->where('slug', '!=', $city->slug)
                    ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                    ->orderBy('distance', 'ASC')
                    ->limit(10)
                    ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) {
            return $city->produits()->with([
            'structure:id,name',
            'adresse',
            'discipline:id,name,slug',
            'activite:id,titre',
            'criteres:id,activite_id,produit_id,critere_id,valeur',
            'criteres.critere:id,nom',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
            ])->get();
        });

        $produitsFromCity = $city->produits()->with([
            'structure:id,name',
            'adresse',
            'discipline:id,name,slug',
            'activite:id,titre',
            'criteres:id,activite_id,produit_id,critere_id,valeur',
            'criteres.critere:id,nom',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])
        ->get();

        $flattenedDisciplines = $produitsFromCity->merge($citiesAroundProducts)->pluck('discipline')->unique();

        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,code_postal',
                'structuretype:id,name,slug',
                'activites',
                'activites.discipline:id,name,slug',
                'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])
            ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
            ->get();
        });

        $structuresFromCity = $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,code_postal',
                'structuretype:id,name,slug',
                'activites',
                'activites.discipline:id,name,slug',
                'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])
        ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
        ->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Show', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'produits' => $produits,
            'flattenedDisciplines' => $flattenedDisciplines,
            'structures' => $structures,
            'filters' => request()->all(['discipline']),
        ]);
    }

    protected function radians($degrees)
    {
        return $degrees * pi() / 180;
    }

}
