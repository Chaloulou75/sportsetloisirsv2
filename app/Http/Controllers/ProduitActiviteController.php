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
    public function show(StructureProduit $structureProduit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StructureProduit $structureProduit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StructureProduit $structureProduit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StructureProduit $structureProduit)
    {
        //
    }

    public function duplicateProduit($produitId)
    {
        $originalProduit = StructureProduit::findOrFail($produitId);

        // Create a new instance of the StructureProduit model
        $newProduit = new StructureProduit;

        // Copy the properties of the original produit to the new produit
        $newProduit->fill($originalProduit->toArray());

        // Set the new produit's ID to null to create a new record
        $newProduit->id = null;

        // Save the new produit to the database
        $newProduit->save();

        // Redirect to the index page or wherever you want to go
        return Redirect::back();
    }
}
