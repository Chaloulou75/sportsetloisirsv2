<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;

class StructurePlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure)
    {
        $structure = Structure::with(['disciplines', 'categories', 'activites', 'produits'])->where('id', $structure->id)->firstOrFail();

        return Inertia::render('Plannings/Index', [
            'structure' => $structure,
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
    public function store(Structure $structure, Request $request)
    {
        //
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
        //
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
