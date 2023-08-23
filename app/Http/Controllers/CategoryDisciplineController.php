<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;

class CategoryDisciplineController extends Controller
{
    public function store(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorieNotInId = $request->input('categorieNotIn');
        $categorieNotIn = Categorie::findOrFail($categorieNotInId);
        $discipline->categories()->attach($categorieNotIn);

        return to_route('admin.edit', $discipline)->with('success', 'Catégorie ajoutée');
    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorieInId = $request->input('categorieIn');
        $categorieIn = Categorie::findOrFail($categorieInId);

        $disciplineCategorie = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('categorie_id', $categorieIn->id)->first();
        $discCatCrit = LienDisciplineCategorieCritere::with('valeurs')->where('categorie_id', $disciplineCategorie->id)->get();


        foreach ($discCatCrit as $item) {
            if ($item->valeurs->isNotEmpty()) {
                foreach ($item->valeurs as $valeur) {
                    $valeur->delete();
                }
            }
        }


        if($discCatCrit->isNotEmpty()) {
            foreach($discCatCrit as $critere) {
                $critere->delete();
            }
        }

        $discipline->categories()->detach($categorieIn);

        return to_route('admin.edit', $discipline)->with('success', 'Catégorie supprimée, ainsi que les critères et valeurs associées à cette catégorie.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline)
    {
        $request->validate([
            'nom_categorie_client' => ['required', 'string', 'max:255'],
            'nom_categorie_pro' => ['required', 'string', 'max:255'],
            'id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
        ]);

        $categorieDiscipline = LienDisciplineCategorie::where('id', $request->id)->firstOrFail();

        $categorieDiscipline->update([
            'nom_categorie_client' => $request->nom_categorie_client,
            'nom_categorie_pro' => $request->nom_categorie_pro,
        ]);
        return to_route('admin.edit', $discipline)->with('success', 'Catégorie mise à jour');
    }
}
