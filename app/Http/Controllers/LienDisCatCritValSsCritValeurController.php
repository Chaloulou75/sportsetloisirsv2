<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LiensDisCatCritValSsCrit;
use App\Models\LiensDisCatCritValSsCritValeur;

class LienDisCatCritValSsCritValeurController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LiensDisCatCritValSsCrit $souscritere)
    {
        $sousCritere = LiensDisCatCritValSsCrit::with('critere_valeur.critere.discipline')->findOrFail($souscritere->id);

        $discipline = $sousCritere->critere_valeur->critere->discipline->slug;

        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'dccValSsCritId' => ['required', Rule::exists('liens_dis_cat_crit_val_sous_criteres', 'id')],
        ]);

        $sousCritVal = LiensDisCatCritValSsCritValeur::create([
            'dcc_val_ss_crit_id' => $request->dccValSsCritId,
            'valeur' => $request->valeur,
            'defaut' => 0,
        ]);

        return to_route('admin.edit', $discipline)->with('success', 'Valeur du sous critère ajoutée');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LiensDisCatCritValSsCritValeur $sousvaleur)
    {
        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'id' => ['required', Rule::exists('liens_dis_cat_crit_val_ss_crit_valeur', 'id')],
        ]);

        $dccSsCritValeur = LiensDisCatCritValSsCritValeur::with(['sous_critere.critere_valeur.critere.discipline'])->findOrFail($sousvaleur->id);

        $discipline = $dccSsCritValeur->sous_critere->critere_valeur->critere->discipline->slug;

        $dccSsCritValeur->update(['valeur' => $request->valeur]);

        return to_route('admin.edit', $discipline)->with('success', 'Valeur du sous critère modifiée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LiensDisCatCritValSsCritValeur $sousvaleur)
    {
        $dccSsCritValeur = LiensDisCatCritValSsCritValeur::with(['sous_critere.critere_valeur.critere.discipline'])->findOrFail($sousvaleur->id);

        $discipline = $dccSsCritValeur->sous_critere->critere_valeur->critere->discipline->slug;

        $dccSsCritValeur->delete();

        return to_route('admin.edit', $discipline)->with('success', 'Valeur supprimée');
    }
}
