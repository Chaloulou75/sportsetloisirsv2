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

        $strCatTarif = $structure->cat_tarifs()->create([
            'categorie_id' => $request->categorie_id,
            'dis_cat_tar_typ_id' => $request->tarif_type['id'],
            'titre' => $request->titre,
            'description' => $request->description,
            'amount' => $request->amount
        ]);

        foreach ($request->attributs as $key => $valeur) {
            $strCatTarifAttribut = $this->createAttribute($strCatTarif, $key, $valeur);

            if ($strCatTarifAttribut !== null && $request->sousattributs) {
                $this->createSousAttributs($strCatTarifAttribut, $request->sousattributs);
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

            foreach ($request->attributs as $key => $valeur) {
                $strCatTarifAttribut = $this->createAttribute($tarif, $key, $valeur);

                if ($strCatTarifAttribut !== null && $request->sousattributs) {
                    $this->createSousAttributs($strCatTarifAttribut, $request->sousattributs);
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

    protected function createAttribute($strCatTarif, $key, $valeur)
    {
        if (is_string($valeur) || is_numeric($valeur)) {
            return $strCatTarif->attributs()->create([
                'cat_tar_att_id' => $key,
                'valeur' => $valeur
            ]);
        } elseif (is_array($valeur)) {
            if (isset($valeur['valeur'])) {
                // Single array with 'valeur' key
                return $strCatTarif->attributs()->create([
                    'cat_tar_att_id' => $key,
                    'dis_cat_tar_att_valeur_id' => $valeur['id'] ?? null,
                    'valeur' => $valeur['valeur']
                ]);
            } elseif (!empty(array_filter($valeur, 'is_array'))) {
                // Array of arrays
                $createdAttributes = [];
                foreach ($valeur as $innerAttribut) {
                    if (is_array($innerAttribut) && isset($innerAttribut['valeur'])) {
                        $createdAttributes[] = $strCatTarif->attributs()->create([
                            'cat_tar_att_id' => $innerAttribut['cat_tar_att_id'] ?? $key,
                            'dis_cat_tar_att_valeur_id' => $innerAttribut['id'] ?? null,
                            'valeur' => $innerAttribut['valeur']
                        ]);
                    }
                }
                return $createdAttributes;
            }
        }
    }

    protected function createSousAttributs($strCatTarifAttribut, $sousattributs)
    {
        // If $strCatTarifAttribut is an array, process each attribute
        if (is_array($strCatTarifAttribut)) {
            foreach ($strCatTarifAttribut as $attribute) {
                $this->processSousAttributsForAttribute($attribute, $sousattributs);
            }
        } else {
            // If it's a single attribute, process it directly
            $this->processSousAttributsForAttribute($strCatTarifAttribut, $sousattributs);
        }
    }

    protected function processSousAttributsForAttribute($attribute, $sousattributs)
    {
        $tarAttribut = LienDisCatTartypAttribut::withWhereHas('sous_attributs')
            ->find($attribute->cat_tar_att_id);

        if (!$tarAttribut) {
            return;
        }

        foreach ($tarAttribut->sous_attributs as $sousAttribut) {
            foreach ($sousattributs as $sousAttId => $sousAttributValeur) {
                if ($sousAttribut->id === $sousAttId) {
                    $this->createSingleSousAttribut($attribute, $sousAttId, $sousAttributValeur);
                }
            }
        }
    }

    protected function createSingleSousAttribut($strCatTarifAttribut, $sousAttId, $sousAttributValeur)
    {
        if (is_string($sousAttributValeur) || is_numeric($sousAttributValeur)) {
            return $strCatTarifAttribut->sous_attributs()->create([
                'sousattribut_id' => $sousAttId,
                'valeur' => $sousAttributValeur
            ]);
        } elseif (is_array($sousAttributValeur)) {
            if (isset($sousAttributValeur['valeur'])) {
                // Single array with 'valeur' key
                return $strCatTarifAttribut->sous_attributs()->create([
                    'sousattribut_id' => $sousAttId,
                    'ss_att_valeur_id' => $sousAttributValeur['id'] ?? null,
                    'valeur' => $sousAttributValeur['valeur']
                ]);
            } elseif (!empty(array_filter($sousAttributValeur, 'is_array'))) {
                // Array of arrays
                $createdSousAttributs = [];
                foreach ($sousAttributValeur as $innerSsAttribut) {
                    if (is_array($innerSsAttribut) && isset($innerSsAttribut['valeur'])) {
                        $createdSousAttributs[] = $strCatTarifAttribut->sous_attributs()->create([
                            'sousattribut_id' => $sousAttId,
                            'ss_att_valeur_id' => $innerSsAttribut['id'] ?? null,
                            'valeur' => $innerSsAttribut['valeur']
                        ]);
                    }
                }
                return $createdSousAttributs;
            }
        }
    }
}
