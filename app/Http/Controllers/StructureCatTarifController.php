<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureCatTarif;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;

class StructureCatTarifController extends Controller
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
    public function store(Request $request, Structure $structure): RedirectResponse
    {
        $request->validate([
            'categorie.id' => ['required', Rule::exists(LienDisciplineCategorie::class, 'id')],
            'tarif_type.id' => ['required', Rule::exists(LienDisCatTariftype::class, 'id')],
            'titre' => ['nullable', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'attributs' => ['nullable'],
            'sousattributs' => ['nullable'],
            'amount' => ['required', 'numeric'],
            'produits' => ['nullable'],
        ]);

        // dd($request->attributs, $request->sousattributs);

        $catTarif = StructureCatTarif::create([
            'structure_id' => $structure->id,
            'categorie_id' => $request->categorie['id'],
            'dis_cat_tar_typ_id' => $request->tarif_type['id'],
            'titre' => $request->titre,
            'description' => $request->description,
            'amount' => $request->amount
        ]);

        foreach($request->attributs as $key => $valeur) {
            //check if $valeur is string or Array
            if(is_string($valeur)) {
                $attribut = $catTarif->attributs()->create([
                   'cat_tar_att_id' => $key,
                   'valeur' => $valeur
                ]);
            } else {
                $attribut = $catTarif->attributs()->create([
                    'cat_tar_att_id' => $key,
                    'dis_cat_tar_att_valeur_id' => $valeur['id'],
                    'valeur' => $valeur['valeur']
                ]);
            }
            // if($request->sousattributs) {
            //     dd($request->sousattributs)
            //     //$attribut->sousattributs()->create([]);

            // }
        }


        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été enregistré pour vos produits");

    }

    /**
     * Display the specified resource.
     */
    public function show(StructureCatTarif $structureCatTarif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StructureCatTarif $structureCatTarif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StructureCatTarif $structureCatTarif)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StructureCatTarif $structureCatTarif)
    {
        //
    }
}
