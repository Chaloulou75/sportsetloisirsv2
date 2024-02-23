<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisCatTariftype;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTarBookingField;
use App\Models\LienDisCatTarBookingFieldSousField;
use Illuminate\Http\RedirectResponse;

class LienDisCatTarBookingFieldSousFieldController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
            'type_champ' => ['required'],
        ]);

        $bookingfield->sous_fields()->create([
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'sous champ ajouté');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldSousField $sousField)
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $sousField->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'sous champ mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldSousField $sousField)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $sousField->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'sous champ supprimé');
    }
}
