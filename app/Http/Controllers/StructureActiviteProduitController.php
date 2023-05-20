<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class StructureActiviteProduitController extends Controller
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
    public function store(Request $request, Structure $structure, StructureActivite $activite)
    {
        $request->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'categorie_id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'criteres' => 'nullable',
            'adresse' => ['nullable', Rule::exists('structure_adresse', 'id')],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'date' => ['nullable'],
            'time' => ['nullable'],
        ]);

        if($request->date || $request->time) {

            $dayopen = Carbon::parse($request->date[0])->format('Y-m-d');
            $dayclose = Carbon::parse($request->date[1])->format('Y-m-d');

            $heureopen = $request->time[0]['hours'];
            $minuteopen = $request->time[0]['minutes'];
            // Construct the time string in HH:ii:ss format
            $houropen = sprintf('%02d:%02d', $heureopen, $minuteopen);

            $heureclose = $request->time[1]['hours'];
            $minuteclose = $request->time[1]['minutes'];
            // Construct the time string in HH:ii:ss format
            $hourclose = sprintf('%02d:%02d', $heureclose, $minuteclose);

            $dayTime = StructureHoraire::firstOrCreate([
                'structure_id' => $structure->id,
                'dayopen' => $dayopen,
                'dayclose' => $dayclose,
                'houropen' => $houropen,
                'hourclose' => $hourclose,
            ]);
        }

        $structureProduit = StructureProduit::create([
                    'structure_id' => $structure->id,
                    'discipline_id' => $activite->discipline_id,
                    'categorie_id' => $activite->categorie_id,
                    'activite_id' => $activite->id,
                    "actif" => 1,
                    'lieu_id' => $request->adresse ?? $structure->adresses->first()->id,
                    'horaire_id' => $dayTime->id,
                    // 'tarif_id' => $structureTarif->id,
                    'reservable' => 0,
                ]);

        $criteres = LienDisciplineCategorieCritere::where('discipline_id', $request->discipline_id)->where('categorie_id', $request->categorie_id)->get();

        $criteresValues = $request->criteres;

        foreach ($criteresValues as $key => $critereValue) {
            $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $key)->first();

            if (isset($critereValue)) {
                $structureProduitCriteres = StructureProduitCritere::create([
                    'structure_id' => $structure->id,
                    'discipline_id' => $request->discipline_id,
                    'categorie_id' => $request->categorie_id,
                    'activite_id' => $activite->id,
                    'produit_id' => $structureProduit->id,
                    'critere_id' => $key,
                    'valeur' => $critereValue ?? $defaut->valeur,
                ]);
            }
        }

        // newAdresse
        if($request->address) {

            $city= City::where('code_postal', $request->zip_code)->firstOrFail();
            $cityId = $city->id;

            $validatedAddress = [
                'structure_id' => $structure->id,
                'name' => $structure->name,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'city' => $request->city,
                'country' => $request->country,
                'city_id' => $cityId,
                'country_id' => $structure->country_id,
                'address_lat' => $request->address_lat,
                'address_lng' => $request->address_lng,
                'phone' => $structure->phone1,
                'email' => $structure->email,
            ];

            $structureAddress = StructureAddress::create($validatedAddress);

            $structureProduit->update(['lieu_id' => $structureAddress->id]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StructureProduit $produit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        $produit = StructureProduit::where('id', $produit->id)->firstOrFail();

        $produitCriteres = StructureProduitCritere::where('produit_id', $produit->id)->get();

        if(isset($produitsCriteres)) {
            foreach($produitCriteres as $critere) {
                $critere->delete();
            }
        }

        $produit->delete();

        return Redirect::back()->with('success', "Le produit a bien été supprimé");
    }

    public function duplicate(Structure $structure, StructureActivite $activite, StructureProduit $produit)
    {
        $originalProduit = StructureProduit::with('criteres')->where('id', $produit->id)->firstOrFail();

        $originalProduitCriteres = StructureProduitCritere::where('produit_id', $originalProduit->id)->get();

        $newProduit = new StructureProduit();
        $newProduit->structure_id = $originalProduit->structure_id;
        $newProduit->discipline_id = $originalProduit->discipline_id;
        $newProduit->categorie_id = $originalProduit->categorie_id;
        $newProduit->activite_id = $originalProduit->activite_id;
        $newProduit->lieu_id = $originalProduit->lieu_id;
        $newProduit->actif = $originalProduit->actif;
        $newProduit->horaire_id = $originalProduit->horaire_id;
        $newProduit->tarif_id = $originalProduit->tarif_id;
        $newProduit->reservable = $originalProduit->reservable;
        $newProduit->id = null;
        $newProduit->save();

        foreach($originalProduitCriteres as $produitCritere) {
            $newProduitCritere = new StructureProduitCritere();
            $newProduitCritere->structure_id = $produitCritere->structure_id;
            $newProduitCritere->discipline_id = $produitCritere->discipline_id;
            $newProduitCritere->categorie_id = $produitCritere->categorie_id;
            $newProduitCritere->activite_id = $produitCritere->activite_id;
            $newProduitCritere->produit_id = $newProduit->id;
            $newProduitCritere->critere_id = $produitCritere->critere_id;
            $newProduitCritere->valeur = $produitCritere->valeur;
            $newProduitCritere->defaut = $produitCritere->defaut;
            $newProduitCritere->id = null;
            $newProduitCritere->save();
        }

        return Redirect::back()->with('success', "Le produit a bien été dupliqué");
    }
}
