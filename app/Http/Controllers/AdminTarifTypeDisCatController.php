<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;

class AdminTarifTypeDisCatController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListeTarifType $tarifType): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $disCats = LienDisciplineCategorie::with('tarif_types')->get();

        $filteredDisCats = $disCats->reject(function ($disCat) use ($tarifType) {
            return $disCat->tarif_types->contains('tarif_type_id', $tarifType->id);
        });

        if($filteredDisCats->isNotEmpty()) {
            $filteredDisCats->each(function ($disCat) use ($tarifType) {
                $disCat->tarif_types()->create([
                    'discipline_id' => $disCat->discipline_id,
                    'tarif_type_id' => $tarifType->id,
                    'nom' => $tarifType->type,
                ]);
            });
        }
        return to_route('admin.tarifs.index')->with('success', 'Type de tarif lié à tous les couples "disciplines- catégories"');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListeTarifType $tarifType): RedirectResponse
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $disCatTarifs = LienDisCatTariftype::where('tarif_type_id', $tarifType->id)->get();

        if($disCatTarifs->isNotEmpty()) {
            foreach($disCatTarifs as $disCatTar) {
                $disCatTar->delete();
            }
        }

        return to_route('admin.tarifs.index')->with('success', 'Type de tarif délié à tous les couples "disciplines- catégories"');

    }
}
