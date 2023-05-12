<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StructureProduit;
use Illuminate\Support\Facades\Redirect;

class ProduitActiviteController extends Controller
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
    public function store(Request $request)
    {
        //
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
    public function destroy(StructureProduit $produit)
    {
        $produit = StructureProduit::where('id', $produit->id)->firstOrFail();
        $produit->delete();

        return Redirect::back()->with('success', "Le produit a bien été supprimé");
    }

    public function duplicate(StructureProduit $produit)
    {

        $originalProduit = StructureProduit::where('id', $produit->id)->firstOrFail();
        $newProduit = new StructureProduit();
        $newProduit->fill($originalProduit->toArray());
        $newProduit->id = null;
        $newProduit->save();

        return Redirect::back()->with('success', "Le produit a bien été dupliqué");
    }
}
