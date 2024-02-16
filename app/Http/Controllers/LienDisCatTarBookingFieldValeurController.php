<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTarBookingField;
use App\Models\LienDisCatTarBookingFieldValeur;

class LienDisCatTarBookingFieldValeurController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $bookingfield->valeurs()->create([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'valeur ajoutée au champ');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield,LienDisCatTarBookingFieldValeur $valeur): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'valeur' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $valeur->update([
            'valeur' => $request->valeur,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'valeur du champ mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $valeur->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'valeur supprimée');
    }
}
