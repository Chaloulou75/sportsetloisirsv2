<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class CategoryDisciplineCritereValeurController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LienDisciplineCategorieCritere $critere): RedirectResponse
    {
        $critere = LienDisciplineCategorieCritere::with(['discipline', 'categorie'])->findOrFail($critere->id);

        $validatedData = $request->validate([
            'valeur' => ['required'],
            'disciplineCategorieCritereId' => ['required', Rule::exists('liens_disciplines_categories_criteres', 'id')],
        ]);

        if (is_array($validatedData['valeur'])) {
            $validatedData['valeur'] = json_encode($validatedData['valeur']);
        }

        $lienDisCatCritVal = LienDisciplineCategorieCritereValeur::create([
            'discipline_categorie_critere_id' => $validatedData['disciplineCategorieCritereId'],
            'valeur' => $validatedData['valeur'],
            'defaut' => 0,
            'inclus_all' => false,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $critere->discipline, 'categorie' => $critere->categorie])->with('success', 'Valeur du critère ajoutée');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisciplineCategorieCritereValeur $lienDisCatCritValeur): RedirectResponse
    {
        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'ordre' => ['nullable', 'numeric'],
            'id' => ['required', Rule::exists('liens_disciplines_categories_criteres_valeurs', 'id')],
            'inclus_all' => 'nullable|boolean:0,1,true,false',
        ]);

        $lienDisCatCritValeur = LienDisciplineCategorieCritereValeur::with(['critere.discipline'])->findOrFail($lienDisCatCritValeur->id);

        $discipline = $lienDisCatCritValeur->critere->discipline->slug;
        $categorie = $lienDisCatCritValeur->critere->categorie;

        $lienDisCatCritValeur->update([
            'valeur' => $request->valeur,
            'ordre' => $request->ordre,
            'inclus_all' => $request->inclus_all,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur du critère modifiée');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritereValeur $lienDisCatCritValeur): RedirectResponse
    {
        $valeur = LienDisciplineCategorieCritereValeur::with(['critere', 'critere.discipline'])->findOrFail($lienDisCatCritValeur->id);

        $discipline = $valeur->critere->discipline->slug;
        $categorie = $valeur->critere->categorie;

        $valeur->delete();

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur supprimée');

    }
}
