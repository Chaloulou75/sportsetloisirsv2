<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $departements = Departement::with(['structures:id,name,slug,description,city,zip_code,address,address_lat,address_lng'])
                        ->select(['id', 'departement', 'numero'])
                        ->withCount('structures')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Departements/Index', [
            'departements' => $departements,
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
    public function show(Departement $departement)
    {
        $departement = Departement::with(['structures:id,name,slug,structuretype_id,description,city,zip_code,address,address_lat,address_lng', 'structures.structuretype:id,name,slug'])
                                    ->where('numero', $departement->numero)
                                    ->select(['id', 'numero', 'departement', 'prefixe', 'view_count'])
                                    ->withCount('structures')
                                    ->first();

        $departement->timestamp = false;
        $departement->increment('view_count');
        // dd($city);
        return Inertia::render('Departements/Show', [
            'departement'=> $departement,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        //
    }
}
