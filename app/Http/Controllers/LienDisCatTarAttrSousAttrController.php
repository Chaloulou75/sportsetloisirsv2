<?php

namespace App\Http\Controllers;

use App\Models\TypeChamp;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisCatTarAttrValeur;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTartypAttribut;
use App\Models\LienDisCatTarAttrSousAttr;

class LienDisCatTarAttrSousAttrController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrValeur $valeur): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
            'type_champ.id' => ['required', Rule::exists(TypeChamp::class, 'id')],
            'type_champ.type' => ['required', 'String'],
        ]);

        $valeur->sous_attributs()->create([
            'cat_tar_att_id' => $attribut->id,
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
            'type_champ_id' => $request->type_champ['id'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous Attribut ajouté');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrValeur $valeur, LienDisCatTarAttrSousAttr $sousAttribut): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $sousAttribut->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous Attribut modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTartypAttribut $attribut, LienDisCatTarAttrValeur $valeur, LienDisCatTarAttrSousAttr $sousAttribut): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $sousAttribut->delete();

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Sous Attribut supprimé');

    }
}
