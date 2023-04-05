<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $cities = City::select(['id', 'ville', 'ville_formatee'])
                        ->withCount('structures')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Villes/Index', [
            'cities' => $cities,
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
        $city = City::with(['structures:id,name,slug,structuretype_id,description,city,zip_code,address,address_lat,address_lng', 'structures.structuretype:id,name'])
                            ->where('ville_formatee', $city->ville_formatee)
                            ->select(['id', 'ville', 'ville_formatee', 'nom_departement', 'view_count'])
                            ->withCount('structures')
                            ->first();


        $city->timestamp = false;
        $city->increment('view_count');
        // dd($city);
        return Inertia::render('Villes/Show', [
            'city'=> $city,
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
}
