<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use App\Models\LienDisCatTariftype;
use App\Models\LienDisCatTartypAttribut;

class AdminTarifTypeDisCatAttributController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListeTarifType $tarifType, LienDisCatTartypAttribut $attribut)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $disCatTarifs = LienDisCatTariftype::with('tarif_attributs')->where('tarif_type_id', $tarifType->id)->get();

        $attributToDuplicate = LienDisCatTartypAttribut::with(['valeurs', 'sous_attributs', 'sous_attributs.valeurs'])->findOrFail($attribut->id);

        foreach($disCatTarifs as $disCatTarif) {
            $existingAttribut = $disCatTarif->tarif_attributs()->where('nom', $attributToDuplicate->nom)->first();
            if (!$existingAttribut) {
                $disCatTarifAttr = $disCatTarif->tarif_attributs()->create([
                    'nom' => $attributToDuplicate->nom,
                    'type_champ_form' => $attributToDuplicate->type_champ_form,
                    'ordre' => $attributToDuplicate->ordre ?? null,
                ]);
                if($attributToDuplicate->valeurs->isNotEmpty()) {

                    foreach($attributToDuplicate->valeurs as $valeur) {
                        $disCatTarifAttr->valeurs()->create([
                            'valeur' => $valeur->valeur,
                            'ordre' => $valeur->ordre ?? null,
                        ]);
                    }
                }

                if($attributToDuplicate->sous_attributs->isNotEmpty()) {
                    foreach($attributToDuplicate->sous_attributs as $sousAttribut) {
                        $disCatTarAttSsAttr = $disCatTarifAttr->sous_attributs()->create([
                            'att_valeur_id' => $sousAttribut->att_valeur_id ?? null,
                            'nom' => $sousAttribut->nom,
                            'type_champ_form' => $sousAttribut->type_champ_form,
                        ]);

                        if($sousAttribut->valeurs->isNotEmpty()) {
                            foreach($sousAttribut->valeurs as $sousAttributValeur) {
                                $disCatTarAttSsAttrValeur = $disCatTarAttSsAttr->valeurs()->create([
                                    'valeur' => $sousAttributValeur->valeur,
                                    'ordre' => $sousAttributValeur->ordre ?? null,
                                    'inclus_all' => $sousAttributValeur->inclus_all ?? false,
                                ]);
                            }
                        }

                    }
                }

            }
        }

        return to_route('admin.tarifs.index')->with('success', 'Attribut de type de tarif lié à tous les couples "disciplines- catégories"');
    }
}
