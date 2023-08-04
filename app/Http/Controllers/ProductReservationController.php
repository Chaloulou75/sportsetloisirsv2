<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StructureTarif;
use App\Models\StructureProduit;
use App\Models\StructurePlanning;
use Illuminate\Support\Facades\Redirect;

class ProductReservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $produit = StructureProduit::where('id', $request->produit)->first();
        $planning = StructurePlanning::where('id', $request->planning)->first();
        $tarif = StructureTarif::where('id', $request->formule)->first();

        // dd($produit, $planning, $tarif);

        return Redirect::back()->with('success', "La demande a été envoyée");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
