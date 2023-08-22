<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class CategoryDisciplineCritereController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'critere.id' => ['required', Rule::exists('liste_criteres', 'id')],
            'categorie.id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'type_champ.type' => ['required', 'string', 'max:255'],
        ]);

        $discCat = LienDisciplineCategorie::with('discipline')->where('id', $request->categorie['id'])->firstOrFail();

        LienDisciplineCategorieCritere::create([
            "discipline_id" => $discCat->discipline_id,
            "categorie_id" => $request->categorie['id'],
            "critere_id" => $request->critere['id'],
            "nom" => $request->critere['nom'],
            "type_champ_form" => $request->type_champ['type'],
        ]);

        return to_route('admin.edit', $discCat->discipline)->with('success', 'Critère ajouté avec succès');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritere $lienDisciplineCategorieCritere)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'valeurs'])->where('id', $lienDisciplineCategorieCritere->id)->firstOrFail();

        if($discCatCritere->valeurs->isNotEmpty()) {
            foreach ($discCatCritere->valeurs as $valeur) {
                $valeur->delete();
            }
        }

        $discCatCritere->delete();
        return to_route('admin.edit', $discCatCritere->discipline)->with('success', 'Critère et valeurs associés supprimés');

    }
}
