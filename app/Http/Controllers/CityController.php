<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();
        $familles = Famille::select(['id', 'name', 'slug'])->get();

        $cities = City::with('structures')->select(['id', 'ville', 'ville_formatee', 'code_postal'])
                        ->withCount('structures')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Villes/Index', [
            'cities' => $cities,
            'familles' => $familles,
            'structuresCount' => $structuresCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $familles = Famille::select(['id', 'name', 'slug'])->get();

        $city = City::with(['structures', 'structures.disciplines', 'structures.disciplines.discipline'])
                    ->select(['id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                    ->where('id', $city->id)
                    ->withCount('structures')
                    ->first();

        $citiesAround = City::with('structures')
                    ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                    ->selectRaw("
                        (6366 * acos(
                            cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) +
                            sin(radians({$city->latitude})) * sin(radians(latitude))
                        )) AS distance
                    ")
                    ->where('id', '!=', $city->id)
                    ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                    ->orderBy('distance', 'ASC')
                    ->limit(10)
                    ->get();

        $structures = $city->structures->load([
            'famille:id,name',
            'creator:id,name',
            'users:id,name',
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines',
            'disciplines.discipline:id,name,slug',
            'categories:id,categorie_id',
            'activites',
            'activites.discipline',
            'activites.categorie',
            'produits',
            'produits.criteres',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])
        ->map(function ($structure) {
            $disciplinesCount = $structure->disciplines->count();
            $activitiesCount = $structure->activites->count();
            $produitsCount = $structure->produits->count();

            return [
                'id' => $structure->id,
                'name' => $structure->name,
                'slug' => $structure->slug,
                'website' => $structure->website,
                'email' => $structure->email,
                'facebook' => $structure->facebook,
                'instagram' => $structure->instagram,
                'youtube' => $structure->youtube,
                'tiktok' => $structure->tiktok,
                'phone1' => $structure->phone1,
                'phone2' => $structure->phone2,
                'address' => $structure->address,
                'zip_code' => $structure->zip_code,
                'city' => $structure->city,
                'city_id' => $structure->city_id,
                'country' => $structure->country,
                'address_lat' => $structure->address_lat,
                'address_lng' => $structure->address_lng,
                'presentation_courte' => $structure->presentation_courte,
                'presentation_longue' => $structure->presentation_longue,
                'structuretype' => $structure->structuretype,
                'departement_id' => $structure->departement_id,
                'user' => $structure->creator,
                'logo' => $structure->logo ? asset($structure->logo) : 'https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
                'categories' => $structure->categories,
                'disciplines' => $structure->disciplines,
                'activites' => $structure->activites,
                'produits' => $structure->produits,
                'tarifs' => $structure->tarifs,
                'disciplines_count' => $disciplinesCount,
                'activites_count' => $activitiesCount,
                'produits_count' => $produitsCount,
            ];
        })->unique();

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Show', [
            'familles' => $familles,
            'city'=> $city,
            'citiesAround' => $citiesAround,
            'structures' => $structures,
            'filters' => request()->all(['discipline']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }


    public function radians($degrees)
    {
        return $degrees * pi() / 180;
    }

}
