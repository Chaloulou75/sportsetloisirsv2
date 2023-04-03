<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;

class CityDisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $city = City::select(['id', 'ville', 'ville_formatee'])
                                ->withCount('structures')
                                ->filter(
                                    request(['discipline'])
                                )
                                ->orderByDesc('structures_count')
                                ->paginate(15)
                                ->withQueryString();

        return Inertia::render('Villes/Disciplines/Show', [
            'city' => $city,
            // 'structuresCount' => $structuresCount,
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
}
