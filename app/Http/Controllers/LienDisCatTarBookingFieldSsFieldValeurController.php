<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTarBookingField;
use App\Models\LienDisCatTarBookingFieldValeur;
use App\Models\LienDisCatTarBookingFieldSousField;
use App\Models\LienDisCatTarBookingFieldSsFieldValeur;

class LienDisCatTarBookingFieldSsFieldValeurController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur, LienDisCatTarBookingFieldSousField $sousField): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $sousField->valeurs()->create([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'valeur ajoutée au sous champ');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur, LienDisCatTarBookingFieldSousField $sousField, LienDisCatTarBookingFieldSsFieldValeur $sousFieldValeur): RedirectResponse
    {

        dd($request->all());

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $sousFieldValeur->update([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'valeur du sous champ mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur, LienDisCatTarBookingFieldSousField $sousField, LienDisCatTarBookingFieldSsFieldValeur $sousFieldValeur): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $sousFieldValeur->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'valeur du sous champ supprimée');
    }
}
