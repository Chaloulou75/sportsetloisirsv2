<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureTarifTypeInfo;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;

class StructurePlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Structure $structure, Request $request)
    {
        dd($request->all());
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, $event)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $event)
    {
        $produit = StructureProduit::where('id', $event)->firstOrFail();

        $produitCriteres = StructureProduitCritere::where('produit_id', $produit->id)->get();

        if(isset($produitsCriteres)) {
            foreach($produitCriteres as $critere) {
                $critere->delete();
            }
        }

        $produit->tarifs()->detach();

        $produit->delete();

        return Redirect::back()->with('success', "Le produit a bien été supprimé");
    }
}
