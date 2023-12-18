<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTartypAttribut;
use App\Models\LienDisCatTarAttrSousAttr;

class LienDisCatTarAttrSousAttrController extends Controller
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
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:3', 'max:255'],
            'type_champ' => ['required'],
        ]);

        $attribut->sous_attributs()->create([
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous Attribut ajouté');

    }

    /**
     * Display the specified resource.
     */
    public function show(LienDisCatTarAttrSousAttr $lienDisCatTarAttrSousAttr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LienDisCatTarAttrSousAttr $lienDisCatTarAttrSousAttr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrSousAttr $sousAttribut): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $sousAttribut->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous Attribut modifié');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrSousAttr $sousAttribut): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $sousAttribut->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous Attribut supprimé');

    }
}
