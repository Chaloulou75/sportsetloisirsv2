<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Http\RedirectResponse;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class StructureDisciplineController extends Controller
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
    public function destroy(Structure $structure, $discipline): RedirectResponse
    {
        $structure = Structure::where('slug', $structure->slug)->firstOrFail();
        $discipline = ListDiscipline::where('id', $discipline)->firstOrFail();

        $structureDiscipline = StructureDiscipline::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->first();

        $categories = StructureCategorie::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $activites = StructureActivite::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $produits = StructureProduit::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $criteres = StructureProduitCritere::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        $plannings = StructurePlanning::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        if($plannings->isNotEmpty()) {
            foreach($plannings as $planning) {
                $planning->delete();
            }
        }

        if($criteres->isNotEmpty()) {
            foreach($criteres as $critere) {
                $critere->delete();
            }
        }

        if($produits->isNotEmpty()) {
            foreach($produits as $produit) {
                $produit->delete();
            }
        }

        if($activites->isNotEmpty()) {
            foreach($activites as $activite) {
                $activite->delete();
            }
        }

        if($categories->isNotEmpty()) {
            foreach($categories as $categorie) {
                $categorie->delete();
            }
        }

        if($structureDiscipline) {
            $structureDiscipline->delete();
        }

        return to_route('structures.activites.index', $structure)->with('success', 'Discipline supprimée de votre liste.');

    }
}
