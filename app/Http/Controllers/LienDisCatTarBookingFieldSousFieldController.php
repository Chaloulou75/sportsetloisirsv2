<?php

namespace App\Http\Controllers;

use App\Models\TypeChamp;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTarBookingField;
use App\Models\LienDisCatTarBookingFieldSousField;
use App\Models\LienDisCatTarBookingFieldValeur;

class LienDisCatTarBookingFieldSousFieldController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
            'type_champ.id' => ['required', Rule::exists(TypeChamp::class, 'id')],
            'type_champ.type' => ['required', 'String'],
        ]);

        $valeur->sous_fields()->create([
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
            'type_champ_id' => $request->type_champ['id'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'sous champ ajouté à la valeur du champ');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur, LienDisCatTarBookingFieldSousField $sousField)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $sousField->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'Nom du sous champ mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield, LienDisCatTarBookingFieldValeur $valeur, LienDisCatTarBookingFieldSousField $sousField)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $sousField->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'sous champ supprimé');
    }
}
