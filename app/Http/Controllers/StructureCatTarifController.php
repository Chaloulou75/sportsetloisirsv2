<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureCatTarif;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTartypAttribut;

class StructureCatTarifController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure): RedirectResponse
    {
        $request->validate([
            'categorie_id' => ['required', Rule::exists(LienDisciplineCategorie::class, 'id')],
            'tarif_type.id' => ['required', Rule::exists(LienDisCatTariftype::class, 'id')],
            'titre' => ['nullable', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'attributs' => ['nullable'],
            'sousattributs' => ['nullable'],
            'amount' => ['required', 'numeric'],
            'produits' => ['nullable'],
        ]);

        // dd($request->attributs);

        $strCatTarif = StructureCatTarif::create([
            'structure_id' => $structure->id,
            'categorie_id' => $request->categorie_id,
            'dis_cat_tar_typ_id' => $request->tarif_type['id'],
            'titre' => $request->titre,
            'description' => $request->description,
            'amount' => $request->amount
        ]);

        foreach($request->attributs as $key => $valeur) {
            if(is_string($valeur) || is_numeric($valeur)) {
                $strCatTarifAttribut = $strCatTarif->attributs()->create([
                   'cat_tar_att_id' => $key,
                   'valeur' => $valeur
                ]);
            } elseif (is_array($valeur) && !empty(array_filter($valeur, 'is_array'))) {
                foreach ($valeur as $innerAttribut) {
                    $strCatTarifAttribut = $strCatTarif->attributs()->create([
                        'cat_tar_att_id' => $innerAttribut['cat_tar_att_id'],
                        'dis_cat_tar_att_valeur_id' => $innerAttribut['id'],
                        'valeur' => $innerAttribut['valeur']
                    ]);
                }
            } else {
                $strCatTarifAttribut = $strCatTarif->attributs()->create([
                    'cat_tar_att_id' => $key,
                    'dis_cat_tar_att_valeur_id' => $valeur['id'],
                    'valeur' => $valeur['valeur']
                ]);
            }
            if($request->sousattributs) {

                $tarAttribut = LienDisCatTartypAttribut::withWhereHas('sous_attributs')->find($strCatTarifAttribut->cat_tar_att_id);

                if($tarAttribut) {
                    foreach($tarAttribut->sous_attributs as $sousAttribut) {
                        foreach($request->sousattributs as $sousAttId => $sousAttributValeur) {
                            if($sousAttribut->id === $sousAttId) {
                                if(is_string($sousAttributValeur) || is_numeric($sousAttributValeur)) {
                                    $strCatTarifAttribut->sous_attributs()->create([
                                        'sousattribut_id' => $sousAttId,
                                        'valeur' => $sousAttributValeur
                                    ]);
                                } elseif (is_array($sousAttributValeur) && !empty(array_filter($sousAttributValeur, 'is_array'))) {
                                        foreach ($sousAttributValeur as $innerSsAttribut) {
                                            $strCatTarifAttribut->sous_attributs()->create([
                                                'sousattribut_id' => $sousAttId,
                                                'ss_att_valeur_id' => $innerSsAttribut['id'],
                                                'valeur' => $innerSsAttribut['valeur']
                                            ]);
                                        }
                                    } else {
                                    $strCatTarifAttribut->sous_attributs()->create([
                                        'sousattribut_id' => $sousAttId,
                                        'ss_att_valeur_id' => $sousAttributValeur['id'],
                                        'valeur' => $sousAttributValeur['valeur']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        if($request->produits) {
            foreach($request->produits as $key => $value) {
                $structureProduit = StructureProduit::find($key);
                if($value === true) {
                    $strCatTarif->produits()->attach($structureProduit->id);
                }
            }
        }

        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été enregistré pour vos produits");

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, StructureCatTarif $tarif): RedirectResponse
    {
        $request->validate([
            'categorie_id' => ['required', Rule::exists(LienDisciplineCategorie::class, 'id')],
            'tarif_type.id' => ['required', Rule::exists(LienDisCatTariftype::class, 'id')],
            'titre' => ['nullable', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'attributs' => ['nullable'],
            'sousattributs' => ['nullable'],
            'amount' => ['required', 'numeric'],
            'produits' => ['nullable'],
        ]);

        $tarif->update([
            'structure_id' => $structure->id,
            'categorie_id' => $request->categorie_id,
            'dis_cat_tar_typ_id' => $request->tarif_type['id'],
            'titre' => $request->titre,
            'description' => $request->description,
            'amount' => $request->amount
        ]);

        if($request->attributs) {
            foreach($tarif->attributs as $attribut) {
                $attribut->delete();
            }

            foreach($request->attributs as $key => $valeur) {
                if(is_string($valeur) || is_numeric($valeur)) {
                    $strCatTarifAttribut = $tarif->attributs()->create([
                       'cat_tar_att_id' => $key,
                       'valeur' => $valeur
                    ]);
                } elseif (is_array($valeur) && !empty(array_filter($valeur, 'is_array'))) {
                    foreach ($valeur as $innerAttribut) {
                        $strCatTarifAttribut = $tarif->attributs()->create([
                            'cat_tar_att_id' => $innerAttribut['cat_tar_att_id'],
                            'dis_cat_tar_att_valeur_id' => $innerAttribut['id'],
                            'valeur' => $innerAttribut['valeur']
                        ]);
                    }
                } else {
                    $strCatTarifAttribut = $tarif->attributs()->create([
                        'cat_tar_att_id' => $key,
                        'dis_cat_tar_att_valeur_id' => $valeur['id'],
                        'valeur' => $valeur['valeur']
                    ]);
                }
                if($request->sousattributs) {
                    $tarAttribut = LienDisCatTartypAttribut::withWhereHas('sous_attributs')->find($strCatTarifAttribut->cat_tar_att_id);

                    if($tarAttribut) {
                        foreach($tarAttribut->sous_attributs as $sousAttribut) {
                            foreach($request->sousattributs as $sousAttId => $sousAttributValeur) {
                                if($sousAttribut->id === $sousAttId) {
                                    if(is_string($sousAttributValeur) || is_numeric($sousAttributValeur)) {
                                        $strCatTarifAttribut->sous_attributs()->create([
                                            'sousattribut_id' => $sousAttId,
                                            'valeur' => $sousAttributValeur
                                        ]);
                                    }  elseif (is_array($sousAttributValeur) && !empty(array_filter($sousAttributValeur, 'is_array'))) {
                                        foreach ($sousAttributValeur as $innerSsAttribut) {
                                            $strCatTarifAttribut->sous_attributs()->create([
                                                'sousattribut_id' => $sousAttId,
                                                'ss_att_valeur_id' => $innerSsAttribut['id'],
                                                'valeur' => $innerSsAttribut['valeur']
                                            ]);
                                        }
                                    } else {
                                        $strCatTarifAttribut->sous_attributs()->create([
                                            'sousattribut_id' => $sousAttId,
                                            'ss_att_valeur_id' => $sousAttributValeur['id'],
                                            'valeur' => $sousAttributValeur['valeur']
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if($request->produits) {
            $tarif->produits()->detach();
            foreach($request->produits as $key => $value) {
                $structureProduit = StructureProduit::find($key);
                if($value === true) {
                    $tarif->produits()->attach($structureProduit->id);
                }
            }
        }

        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été modifié");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, StructureCatTarif $tarif): RedirectResponse
    {
        $tarif->delete();
        return to_route('structures.disciplines.index', $structure)->with('success', "Le tarif a bien été supprimé");
    }
}
