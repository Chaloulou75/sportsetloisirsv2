<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureTarifTypeInfo;
use Illuminate\Support\Facades\Redirect;

class StructureTarifController extends Controller
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
    public function store(Request $request, Structure $structure)
    {
        $request->validate([
            'structure_id' => ['nullable', Rule::exists('structures', 'id')],
            'titre' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'tarifType' => ['nullable', Rule::exists('liste_tarifs_types', 'id')],
            'attributs' => ['nullable'],
            'amount' => ['required', 'numeric'],
            'disciplines' => ['nullable'],
            'categories' => ['nullable'],
            'activites' => ['nullable'],
            'produits' => ['required'],
            'uniteDuree' => ['nullable'],
        ]);

        $structure = Structure::with(['disciplines', 'categories', 'activites', 'produits'])->where('id', $structure->id)->firstOrFail();

        foreach($request->produits as $key => $produitId) {
            if($produitId === true) {
                $structureProduit = StructureProduit::where('id', $key)->first();

                $structureTarif = StructureTarif::create([
                    'structure_id' => $structure->id,
                    'discipline_id' => $structureProduit->discipline_id,
                    'categorie_id' => $structureProduit->categorie_id,
                    'activite_id' => $structureProduit->activite_id,
                    'produit_id' => $structureProduit->id,
                    'type_id' => $request->tarifType,
                    'titre' => $request->titre,
                    'description' => $request->description,
                    'amount' => $request->amount,
                ]);

                $structureProduit->update(['tarif_id' => $structureTarif->id]);

                $tarifType = ListeTarifType::with('tariftypeattributs')->where('id', $structureTarif->type_id)->first();

                foreach($request->attributs as $key => $valeur) {
                    foreach($tarifType->tariftypeattributs as $tariftypeattribut) {
                        if($tariftypeattribut->id === $key) {
                            $tarifAttribut = StructureTarifTypeInfo::create([
                                'structure_id' => $structure->id,
                                'tarif_id' => $structureTarif->id,
                                'type_id' => $tarifType->id,
                                'attribut_id' => $key,
                                'valeur' => $valeur,
                            ]);
                            if($tariftypeattribut->attribut === 'Duree') {
                                $tarifAttribut->update(['valeur'=> $valeur . ' ' . $request->uniteDuree['name']]);
                            }
                        }
                    }
                }
            }
        }

        return Redirect::back()->with('success', "Le tarif a bien été enregistré pour vos produits");

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
    public function destroy(Structure $structure, $tarif)
    {
        $tarif = StructureTarif::with('structureTarifTypeInfos')->where('id', $tarif)->where('structure_id', $structure->id)->firstOrFail();

        $infos = StructureTarifTypeInfo::where('tarif_id', $tarif->id)->get();

        $produits = StructureProduit::where('structure_id', $structure->id)->where('tarif_id', $tarif->id)->get();

        if($produits->isNotEmpty()) {
            foreach($produits as $produit) {
                $produit->update(['tarif_id' => null]);
            }
        }

        if($infos->isNotEmpty()) {
            foreach($infos as $info) {
                $info->delete();
            }
        }

        $tarif->delete();

        return Redirect::back()->with('success', 'Le tarif a bien été supprimé');

    }
}
