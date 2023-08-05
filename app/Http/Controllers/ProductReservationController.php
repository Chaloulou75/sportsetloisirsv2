<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use Illuminate\Support\Facades\Redirect;

class ProductReservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'produit' => ['required', Rule::exists('structures_produits', 'id')],
            'formule' => ['required', Rule::exists('structures_tarifs', 'id')],
            'planning' => ['nullable', Rule::exists('structure_produits_planning', 'id')],
        ]);
        if(!auth()->user()) {
            return redirect()->route('login')->with('error', 'Vous devez vous authentifier pour effectuer la demande');
        }

        $user = auth()->user();
        $produit = StructureProduit::where('id', $request->produit)->first();
        $tarif = StructureTarif::where('id', $request->formule)->first();
        $planning = StructurePlanning::where('id', $request->planning)->first();

        $email = $produit->structure->email;
        $activiteId = $produit->activite->id;
        $activite = StructureActivite::where('id', $activiteId)->first();
        dd($produit, $planning, $tarif, $email, $activite, $user);

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
