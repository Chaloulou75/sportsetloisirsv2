<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisCatTarAttrValeur;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTartypAttribut;

class LienDisCatTarAttrValeurController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $attribut->valeurs()->create([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur ajoutée');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrValeur $valeur): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $valeur->update([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur modifiée');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrValeur $valeur)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $valeur->delete();
        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur supprimée');
    }
}
