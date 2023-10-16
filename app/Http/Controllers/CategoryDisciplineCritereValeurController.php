<?php

namespace App\Http\Controllers;

use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LienDisciplineCategorieCritereValeur;
use App\Models\ListDiscipline;

class CategoryDisciplineCritereValeurController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LienDisciplineCategorieCritere $critere)
    {
        $critere = LienDisciplineCategorieCritere::with('discipline')->where('id', $critere->id)->firstOrFail();
        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'disciplineCategorieCritereId' => ['required', Rule::exists('liens_disciplines_categories_criteres', 'id')],
        ]);

        $lienDisCatCritVal = LienDisciplineCategorieCritereValeur::create([
            'discipline_categorie_critere_id' => $request->disciplineCategorieCritereId,
            'valeur' => $request->valeur,
            'defaut' => 0,
        ]);

        return to_route('admin.edit', $critere->discipline)->with('success', 'Valeur du critère ajoutée');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisciplineCategorieCritereValeur $lienDisCatCritValeur)
    {
        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'id' => ['required', Rule::exists('liens_disciplines_categories_criteres_valeurs', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
        ]);

        $disciplineId = $request->discipline_id;
        $discipline = ListDiscipline::find($disciplineId);

        $lienDisCatCritValeur = LienDisciplineCategorieCritereValeur::where('id', $lienDisCatCritValeur->id)->firstOrFail();

        $lienDisCatCritValeur->update(['valeur' => $request->valeur]);

        return to_route('admin.edit', ['discipline' => $discipline->slug])->with('success', 'Valeur du critère modifiée');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritereValeur $lienDisCatCritValeur)
    {
        $valeur = LienDisciplineCategorieCritereValeur::with(['critere', 'critere.discipline'])->where('id', $lienDisCatCritValeur->id)->firstOrFail();

        $discipline = $valeur->critere->discipline->slug;

        $valeur->delete();

        return to_route('admin.edit', $discipline)->with('success', 'Valeur supprimée');

    }
}
