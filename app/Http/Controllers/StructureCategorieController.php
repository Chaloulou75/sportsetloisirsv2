<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;

class StructureCategorieController extends Controller
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
    public function destroy(Structure $structure, $categorie): RedirectResponse
    {
        $structure = Structure::where('slug', $structure->slug)->firstOrFail();
        $categorie = LienDisciplineCategorie::where('id', $categorie)->firstOrFail();

        $structureCategorie = StructureCategorie::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->first();

        $discipline = $structureCategorie->discipline;

        $activites = StructureActivite::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->get();

        $produits = StructureProduit::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->get();

        $criteres = StructureProduitCritere::where('structure_id', $structure->id)->where('categorie_id', $categorie->id)->get();

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

        if($structureCategorie) {
            $structureCategorie->delete();
        }

        //supprimer StructureDiscipline basé sur structureCategorie if no categories
        $structureDiscipline = StructureDiscipline::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->first();
        $categories = StructureCategorie::where('structure_id', $structure->id)->where('discipline_id', $discipline->id)->get();

        if($categories->isEmpty()) {
            $structureDiscipline->delete();
        };

        return Redirect::route('structures.activites.index', $structure)->with('success', 'Discipline supprimée de votre liste.');

    }
}
