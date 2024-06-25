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
        $categorie = $sousCritere->critere_valeur->critere->categorie;

        $validatedData = $request->validate([
            'valeur' => ['required'],
            'dccValSsCritId' => ['required', Rule::exists('liens_dis_cat_crit_val_sous_criteres', 'id')],
        ]);


        if (is_array($validatedData['valeur'])) {
            $validatedData['valeur'] = json_encode($validatedData['valeur']);
        }

        $sousCritVal = LiensDisCatCritValSsCritValeur::create([
            'dcc_val_ss_crit_id' => $validatedData['dccValSsCritId'],
            'valeur' => $validatedData['valeur'],
            'defaut' => 0,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur du sous critère ajoutée');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LiensDisCatCritValSsCritValeur $sousvaleur)
    {
        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'ordre' => ['nullable', 'numeric'],
            'id' => ['required', Rule::exists('liens_dis_cat_crit_val_ss_crit_valeur', 'id')],
        ]);

        $dccSsCritValeur = LiensDisCatCritValSsCritValeur::with(['sous_critere.critere_valeur.critere.discipline'])->findOrFail($sousvaleur->id);

        $discipline = $dccSsCritValeur->sous_critere->critere_valeur->critere->discipline->slug;
        $categorie = $dccSsCritValeur->sous_critere->critere_valeur->critere->categorie;

        $dccSsCritValeur->update([
            'valeur' => $request->valeur,
            'ordre' => $request->ordre
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur du sous critère modifiée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LiensDisCatCritValSsCritValeur $sousvaleur)
    {
        $dccSsCritValeur = LiensDisCatCritValSsCritValeur::with(['sous_critere.critere_valeur.critere.discipline'])->findOrFail($sousvaleur->id);

        $discipline = $dccSsCritValeur->sous_critere->critere_valeur->critere->discipline->slug;
        $categorie = $dccSsCritValeur->sous_critere->critere_valeur->critere->categorie;

        $dccSsCritValeur->delete();

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur supprimée');
    }
}
