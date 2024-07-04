<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LiensDisCatCritValSsCrit;
use App\Models\LienDisciplineCategorieCritereValeur;
use App\Models\TypeChamp;
use Illuminate\Http\RedirectResponse;

class LienDisCatCritValSsCritController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LienDisciplineCategorieCritereValeur $valeur): RedirectResponse
    {
        $valeur = LienDisciplineCategorieCritereValeur::with('critere.discipline')->findOrFail($valeur->id);

        $discipline = $valeur->critere->discipline->slug;
        $categorie = $valeur->critere->categorie;

        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'type_champ.id' => ['required', Rule::exists(TypeChamp::class, 'id')],
            'type_champ.type' => ['required', 'String'],
        ]);

        $sousCrit = LiensDisCatCritValSsCrit::create([
            'dis_cat_crit_val_id' => $valeur->id,
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
            'type_champ_id' => $request->type_champ['id'],
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur du sous critère ajoutée');

    }

    public function update(Request $request, LiensDisCatCritValSsCrit $souscritere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'unite' => 'required|string|min:1|max:255',
            'min' => 'nullable|integer',
            'max' => 'nullable|integer',
        ]);

        $sousCritere = LiensDisCatCritValSsCrit::with('critere_valeur.critere.discipline')->findOrFail($souscritere->id);

        $discipline = $sousCritere->critere_valeur->critere->discipline->slug;
        $categorie = $sousCritere->critere_valeur->critere->categorie;

        $sousCritere->update([
            'unite' => $request->unite,
            'min' => $request->min,
            'max' => $request->max,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous critère modifié');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LiensDisCatCritValSsCrit $souscritere): RedirectResponse
    {
        $sousCritere = LiensDisCatCritValSsCrit::with('critere_valeur.critere.discipline')->findOrFail($souscritere->id);

        $discipline = $sousCritere->critere_valeur->critere->discipline->slug;
        $categorie = $sousCritere->critere_valeur->critere->categorie;

        $sousCritere->delete();

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous critère supprimé');


    }
}
