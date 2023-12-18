<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTartypAttribut;
use App\Models\LienDisCatTarAttrSousAttr;
use App\Models\LienDisCatTarAttrSousAttrValeur;

class LienDisCatTarAttrSousAttrValeurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrSousAttr $sousAttribut): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $sousAttribut->valeurs()->create([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur ajoutée');

    }

    /**
     * Display the specified resource.
     */
    public function show(LienDisCatTarAttrSousAttrValeur $lienDisCatTarAttrSousAttrValeur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LienDisCatTarAttrSousAttrValeur $lienDisCatTarAttrSousAttrValeur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrSousAttr $sousAttribut, LienDisCatTarAttrSousAttrValeur $valeur): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $valeur->update([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur modifiée');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrSousAttr $sousAttribut, LienDisCatTarAttrSousAttrValeur $valeur)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $valeur->delete();
        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Valeur supprimée');

    }
}
