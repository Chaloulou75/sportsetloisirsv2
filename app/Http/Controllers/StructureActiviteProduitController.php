<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;

class StructureActiviteProduitController extends Controller
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
    public function store(Request $request, Structure $structure, StructureActivite $activite)
    {
        dd($activite);
    }

    /**
     * Display the specified resource.
     */
    public function show(StructureProduit $produit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StructureProduit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StructureProduit $produit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        $produit = StructureProduit::where('id', $produit->id)->firstOrFail();

        $produitCriteres = StructureProduitCritere::where('produit_id', $produit->id)->get();

        if(isset($produitsCriteres)) {
            foreach($produitCriteres as $critere) {
                $critere->delete();
            }
        }

        $produit->delete();

        return Redirect::back()->with('success', "Le produit a bien été supprimé");
    }

    public function duplicate(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        $originalProduit = StructureProduit::with('criteres')->where('id', $produit->id)->firstOrFail();

        $originalProduitCriteres = StructureProduitCritere::where('produit_id', $originalProduit->id)->get();

        $newProduit = new StructureProduit();
        $newProduit->structure_id = $originalProduit->structure_id;
        $newProduit->discipline_id = $originalProduit->discipline_id;
        $newProduit->categorie_id = $originalProduit->categorie_id;
        $newProduit->activite_id = $originalProduit->activite_id;
        $newProduit->lieu_id = $originalProduit->lieu_id;
        $newProduit->actif = $originalProduit->actif;
        $newProduit->horaire_id = $originalProduit->horaire_id;
        $newProduit->tarif_id = $originalProduit->tarif_id;
        $newProduit->reservable = $originalProduit->reservable;
        $newProduit->id = null;
        $newProduit->save();

        foreach($originalProduitCriteres as $produitCritere) {
            $newProduitCritere = new StructureProduitCritere();
            $newProduitCritere->structure_id = $produitCritere->structure_id;
            $newProduitCritere->discipline_id = $produitCritere->discipline_id;
            $newProduitCritere->categorie_id = $produitCritere->categorie_id;
            $newProduitCritere->activite_id = $produitCritere->activite_id;
            $newProduitCritere->produit_id = $newProduit->id;
            $newProduitCritere->critere_id = $produitCritere->critere_id;
            $newProduitCritere->valeur = $produitCritere->valeur;
            $newProduitCritere->defaut = $produitCritere->defaut;
            $newProduitCritere->id = null;
            $newProduitCritere->save();
        }

        return Redirect::back()->with('success', "Le produit a bien été dupliqué");
    }
}
