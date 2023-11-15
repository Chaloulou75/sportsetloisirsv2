<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LiensDisCatCritValSsCrit;
use App\Models\LienDisciplineCategorieCritereValeur;

class LienDisCatCritValSsCritController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LienDisciplineCategorieCritereValeur $valeur)
    {
        $valeur = LienDisciplineCategorieCritereValeur::with('critere.discipline')->findOrFail($valeur->id);

        $discipline = $valeur->critere->discipline->slug;
        $categorie = $valeur->critere->categorie;

        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'type_champ' => ['required', 'String'],
        ]);

        $sousCrit = LiensDisCatCritValSsCrit::create([
            'dis_cat_crit_val_id' => $valeur->id,
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur du sous critère ajoutée');

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
    public function destroy(LiensDisCatCritValSsCrit $souscritere)
    {
        $sousCritere = LiensDisCatCritValSsCrit::with('critere_valeur.critere.discipline')->findOrFail($souscritere->id);

        $discipline = $sousCritere->critere_valeur->critere->discipline->slug;
        $categorie = $sousCritere->critere_valeur->critere->categorie;

        $sousCritere->delete();

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous critère supprimé');


    }
}
