<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTarBookingField;

class LienDisCatTarBookingFieldController extends Controller
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
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType)
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

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'catTarif' => $tarifType])->with('success', 'champ ajouté au tarif');
    }

    /**
     * Display the specified resource.
     */
    public function show(LienDisCatTarBookingField $lienDisCatTarBookingField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LienDisCatTarBookingField $lienDisCatTarBookingField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisCatTarBookingField $lienDisCatTarBookingField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisCatTarBookingField $lienDisCatTarBookingField)
    {
        //
    }
}
