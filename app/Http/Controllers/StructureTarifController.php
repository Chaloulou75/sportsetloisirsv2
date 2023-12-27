<?php

namespace App\Http\Controllers;

use App\Models\LienDisCatTariftype;
use App\Models\LienDisciplineCategorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureTarifTypeInfo;
use Illuminate\Http\RedirectResponse;

class StructureTarifController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure): RedirectResponse
    {
        // dd($request->all(), $structure);
        $request->validate([
            'structure_id' => ['nullable', Rule::exists(Structure::class, 'id')],
            'titre' => ['nullable'],
            'description' => ['nullable'],
            'tarifType' => ['nullable', Rule::exists(ListeTarifType::class, 'id')],
            'attributs' => ['nullable'],
            'amount' => ['required', 'numeric'],
            'disciplines' => ['nullable'],
            'categories' => ['nullable'],
            'activites' => ['nullable'],
            'produits' => ['required'],
            'uniteDuree' => ['nullable'],
        ]);

        $structure = Structure::with(['disciplines', 'categories', 'activites', 'produits'])->findOrFail($structure->id);

        $structureTarif = StructureTarif::create([
            'structure_id' => $structure->id,
            'type_id' => $request->tarifType,
            'titre' => $request->titre ?? "",
            'description' => $request->description ?? "",
            'amount' => $request->amount,
        ]);

        foreach($request->produits as $key => $value) {
            $structureProduit = StructureProduit::where('id', $key)->first();
            if($value === true) {
                $structureTarif->produits()->attach($structureProduit->id);
            }
        }

        $tarifType = ListeTarifType::with('tariftypeattributs')->where('id', $structureTarif->type_id)->first();

        foreach($request->attributs as $key => $valeur) {
            foreach($tarifType->tariftypeattributs as $tariftypeattribut) {
                if($tariftypeattribut->id === $key) {
                    $tarifAttribut = $structureTarif->structureTarifTypeInfos()->create([
                        'structure_id' => $structure->id,
                        'tarif_id' => $structureTarif->id,
                        'type_id' => $tarifType->id,
                        'attribut_id' => $key,
                        'valeur' => $valeur,
                    ]);
                    if($tariftypeattribut->attribut === 'Duree') {
                        $tarifAttribut->update(['unite' => $request->uniteDuree['name']]);
                    }
                }
            }
        }

        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été enregistré pour vos produits");

    }

    public function storewithattributs(Request $request, Structure $structure): RedirectResponse
    {
        // dd($request->all());


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
        dd($request->all());


        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été enregistré pour vos produits");

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, $tarif): RedirectResponse
    {
        $request->validate([
            'titre' => ['nullable'],
            'description' => ['nullable'],
            'tarifType[id]' => ['nullable', 'exists:liste_tarifs_types'],
            'attributs' => ['nullable', 'array'],
            'amount' => ['required', 'numeric'],
            'disciplines' => ['nullable'],
            'categories' => ['nullable'],
            'activites' => ['nullable'],
            'produits' => ['required'],
            'uniteDuree' => ['nullable'],
        ]);

        // $structure = Structure::findOrFail($structure->id);

        $tarif = StructureTarif::with('structureTarifTypeInfos')->findOrFail($tarif);

        $structureTarif = $tarif->updateOrCreate(
            ['structure_id' => $structure->id,],
            ['type_id' => $request->tarifType['id'],
            'titre' => $request->titre ?? "",
            'description' => $request->description ?? "",
            'amount' => $request->amount]
        );

        $structureTarif->produits()->detach();

        foreach($request->produits as $key => $value) {
            $structureProduit = StructureProduit::find($key);
            if($value === true) {
                $structureTarif->produits()->attach($structureProduit->id);
            }
        }

        $tarifType = ListeTarifType::with('tariftypeattributs')->find($structureTarif->type_id);

        foreach($structureTarif->structureTarifTypeInfos as $info) {
            $info->delete();
        }

        foreach($request->attributs as $key => $valeur) {
            foreach($tarifType->tariftypeattributs as $tariftypeattribut) {
                if($tariftypeattribut->id === $key) {
                    $tarifAttribut = $structureTarif->structureTarifTypeInfos()->create(
                        [ 'structure_id' => $structure->id,
                        'tarif_id' => $structureTarif->id,
                        'type_id' => $structureTarif->type_id,
                        'attribut_id' => $key,
                        'valeur' => $valeur ]
                    );
                    if($tariftypeattribut->attribut === 'Duree') {
                        $tarifAttribut->update(['unite' => $request->uniteDuree['name']]);
                    }
                }
            }
        }

        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été mis à jour pour vos produits");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $tarif, $produit): RedirectResponse
    {
        $produit = StructureProduit::findOrFail($produit);
        $tarif = StructureTarif::with('structureTarifTypeInfos')->findOrFail($tarif);

        $tarif->produits()->detach($produit->id);

        return to_route('structures.disciplines.index', $structure)->with('success', 'Le tarif pour ce produit a bien été supprimé');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyTarif(Structure $structure, $tarif): RedirectResponse
    {
        $tarif = StructureTarif::with('structureTarifTypeInfos')->findOrFail($tarif);

        // Si on veut supprimer entierement le tarif:
        $infos = StructureTarifTypeInfo::where('tarif_id', $tarif->id)->get();
        if($infos->isNotEmpty()) {
            foreach($infos as $info) {
                $info->delete();
            }
        }
        $tarif->produits()->detach();
        $tarif->delete();

        return to_route('structures.disciplines.index', $structure)->with('success', 'Le tarif a bien été supprimé');
    }

    public function duplicate(Structure $structure, $tarif, $produit): RedirectResponse
    {
        $produit = StructureProduit::findOrFail($produit);

        $originalTarif = StructureTarif::with('structureTarifTypeInfos')->where('id', $tarif)->where('structure_id', $structure->id)->firstOrFail();

        $originalInfos = StructureTarifTypeInfo::where('tarif_id', $originalTarif->id)->get();

        $newTarif = $originalTarif->replicate();
        $newTarif->save();

        $newTarif->produits()->attach($produit->id);

        foreach($originalInfos as $tarifInfo) {
            $newTarifInfo = new StructureTarifTypeInfo();
            $newTarifInfo->structure_id = $tarifInfo->structure_id;
            $newTarifInfo->tarif_id = $newTarif->id;
            $newTarifInfo->type_id = $tarifInfo->type_id;
            $newTarifInfo->attribut_id = $tarifInfo->attribut_id;
            $newTarifInfo->valeur = $tarifInfo->valeur;
            $newTarifInfo->id = null;
            $newTarifInfo->save();
        }

        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été dupliqué");
    }
}
