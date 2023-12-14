<?php

namespace App\Http\Controllers;

use App\Models\LienDisCatTariftype;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTartypAttribut;

class LienDisCatTariftypeAttributController extends Controller
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
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:3', 'max:255'],
            'type_champ' => ['required'],
        ]);

        $tarifType->tarif_attributs()->create([
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Attribut ajouté');

    }

    /**
     * Display the specified resource.
     */
    public function show(LienDisCatTartypAttribut $lienDisCatTartypAttribut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LienDisCatTartypAttribut $lienDisCatTartypAttribut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisCatTartypAttribut $lienDisCatTartypAttribut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisCatTartypAttribut $lienDisCatTartypAttribut)
    {
        //
    }
}
