<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ReservationAsked;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Mail;
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
            return to_route('login')->with('error', 'Vous devez vous authentifier pour effectuer la demande');
        }

        $user = auth()->user();
        $produit = StructureProduit::where('id', $request->produit)->first();
        $tarif = StructureTarif::where('id', $request->formule)->first();
        $planning = StructurePlanning::where('id', $request->planning)->first();

        $structure = $produit->structure;
        $email = $produit->structure->email;

        $activiteId = $produit->activite->id;
        $activite = StructureActivite::where('id', $activiteId)->first();

        $newReservation = ProductReservation::create([
            'user_id' => $user->id,
            'produit_id' => $produit->id,
            'tarif_id' => $tarif->id,
            'planning_id' => $planning->id,
            'pending' => true,
            'confirmed' => false,
            'finished' => false,
            'cancelled' => false,
        ]);

        dd($newReservation);

        Mail::to($email)->send(new ReservationAsked($structure, $activite, $produit, $planning, $tarif, $user));

        // dd($produit, $planning, $tarif, $email, $activite, $user);

        return Redirect::back()->with('success', "La demande d'information a été envoyée à la structure");

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
