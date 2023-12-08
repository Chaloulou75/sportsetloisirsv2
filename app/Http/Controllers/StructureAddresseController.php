<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\StructureAddress;
use Illuminate\Http\RedirectResponse;

class StructureAddresseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure): RedirectResponse
    {
        request()->validate([
            'address' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'city' => ['required', 'string'],
        ]);

        $structure = Structure::where('id', $structure->id)->firstOrFail();

        $city = City::where('code_postal', $request->zip_code)->firstOrFail();
        $cityId = $city->id;

        $validatedAddress = [
                    'structure_id' => $structure->id,
                    'address' => $request->address,
                    'zip_code' => $request->zip_code,
                    'city' => $request->city,
                    'city_id' => $cityId,
                    'country_id' => $structure->country_id,
                    'country' => $request->country,
                    'address_lat' => $request->address_lat,
                    'address_lng' => $request->address_lng,
                    'phone' => $structure->phone1,
                    'email' => $structure->email,
                    'name' => $structure->name,
                ];

        $structureAddress = StructureAddress::create($validatedAddress);

        return to_route('structures.gestion.index', $structure)->with('success', 'Adresse ajoutée.');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, StructureAddress $adress): RedirectResponse
    {
        request()->validate([
            'address' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'city' => ['required', 'string'],
        ]);

        $city = City::where('code_postal', $request->zip_code)->firstOrFail();
        $cityId = $city->id;

        $validatedAddress = [
            'structure_id' => $structure->id,
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'city_id' => $cityId,
            'country_id' => $structure->country_id,
            'country' => $request->country,
            'address_lat' => $request->address_lat,
            'address_lng' => $request->address_lng,
            'phone' => $structure->phone1,
            'email' => $structure->email,
            'name' => $structure->name,
        ];

        $adress->update($validatedAddress);

        return to_route('structures.gestion.index', $structure)->with('success', 'Adresse modifiée.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, StructureAddress $adress): RedirectResponse
    {
        $adress->delete();

        return to_route('structures.gestion.index', $structure)->with('success', 'Adresse supprimée.');

    }
}
