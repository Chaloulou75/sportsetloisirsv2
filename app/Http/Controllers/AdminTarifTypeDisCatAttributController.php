<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use App\Models\LienDisCatTariftype;

class AdminTarifTypeDisCatAttributController extends Controller
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
    public function store(Request $request, ListeTarifType $tarifType)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:3', 'max:255'],
            'type_champ' => ['required'],
        ]);

        $disCatTarifs = LienDisCatTariftype::with('tarif_attributs')->where('tarif_type_id', $tarifType->id)->get();

        // dd($request->all(), $tarifType, $disCatTarifs);

        foreach($disCatTarifs as $disCatTarif) {
            $existingAttribut = $disCatTarif->tarif_attributs()->where('nom', $request->nom)->first();
            if (!$existingAttribut) {
                $disCatTarif->tarif_attributs()->create([
                    'nom' => $request->nom,
                    'type_champ_form' => $request->type_champ['type'],
                ]);
            }
        }

        return to_route('admin.tarifs.index')->with('success', 'Attribut de type de tarif lié à tous les couples "disciplines- catégories"');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
