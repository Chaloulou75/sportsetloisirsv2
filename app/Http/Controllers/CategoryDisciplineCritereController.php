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

        $discCat = LienDisciplineCategorie::with('discipline:id,name,slug')->findOrFail($request->categorie['id']);

        LienDisciplineCategorieCritere::create([
            "discipline_id" => $discCat->discipline_id,
            "categorie_id" => $request->categorie['id'],
            "critere_id" => $request->critere['id'],
            "nom" => $request->critere['nom'],
            "type_champ_form" => $request->type_champ['type'],
            "visible_back" => true,
            "visible_front" => true,
        ]);

        return to_route('admin.edit', $discCat->discipline->slug)->with('success', 'Critère ajouté avec succès');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisciplineCategorieCritere $critere)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'visible_front' => 'required|boolean:0,1,true,false'
        ]);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'valeurs'])->findOrFail($critere->id);

        $discCatCritere->update([
            'visible_front' => $request->visible_front
        ]);

        return to_route('admin.edit', $discCatCritere->discipline->slug)->with('success', 'Visibilité du critère mise à jour');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritere $lienDisciplineCategorieCritere)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'valeurs'])->findOrFail($lienDisciplineCategorieCritere->id);

        if($discCatCritere->valeurs->isNotEmpty()) {
            foreach ($discCatCritere->valeurs as $valeur) {
                $valeur->delete();
            }
        }

        $discCatCritere->delete();
        return to_route('admin.edit', $discCatCritere->discipline)->with('success', 'Critère et valeurs associés supprimés');

    }
}
