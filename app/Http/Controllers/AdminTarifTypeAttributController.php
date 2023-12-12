<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use Illuminate\Http\RedirectResponse;
use App\Models\ListeTarifTypeAttribut;

class AdminTarifTypeAttributController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListeTarifType $tarif): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'attribut' => ['required', 'string', 'min:3'],
        ]);
        $tarif->tariftypeattributs()->create([
            'attribut' => $request->attribut,
        ]);

        return to_route('admin.tarifs.index')->with('success', 'Attribut de tarif créé');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListeTarifType $tarif, ListeTarifTypeAttribut $attribut): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'attribut' => ['required', 'string', 'min:3'],
        ]);
        $attribut->update([
            'attribut' => $request->attribut,
        ]);

        return to_route('admin.tarifs.index')->with('success', 'Attribut du tarif modifié');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListeTarifType $tarif, ListeTarifTypeAttribut $attribut): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $attribut->delete();

        return to_route('admin.tarifs.index')->with('success', 'Attribut de tarif supprimé');

    }
}
