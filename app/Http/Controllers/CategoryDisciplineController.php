<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\LienDisciplineCategorie;
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
        $discipline->categories()->detach($categorieIn);
        return to_route('admin.edit', $discipline)->with('success', 'Catégorie supprimée');
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
