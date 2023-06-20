<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;

class StructurePlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure)
    {

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
    public function store(Structure $structure, Request $request)
    {
        $dateStart = $request->event['start'];
        $startDate = Carbon::parse($dateStart)->toDateTimeString();

        $dateEnd = $request->event['end'];
        $endDate = Carbon::parse($dateEnd)->toDateTimeString();


        dd($startDate, $endDate);


        $activite = StructureActivite::create([
            'structure_id' => $request->structure_id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'titre' => $request->event['title'] ?? "",
            'actif' => 1,
        ]);

        $produit = StructureProduit::create([
            'structure_id' => $request->structure_id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'activite_id' => $activite->id,
            'actif' => 1,
            'lieu_id' => $structure->adresses->first()->id,
        ]);

        StructurePlanning::create([
            'structure_id' => $request->structure_id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'activite_id' => $activite->id,
            'produit_id' => $produit->id,
            'title' => $request->event['title'] ?? "",
            'start' => $startDate ?? "",
            'end' => $endDate ?? "",
        ]);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $event)
    {
        $produit = StructureProduit::where('id', $event)->firstOrFail();

        $produitCriteres = StructureProduitCritere::where('produit_id', $produit->id)->get();

        if(isset($produitsCriteres)) {
            foreach($produitCriteres as $critere) {
                $critere->delete();
            }
        }

        $produit->tarifs()->detach();

        $produit->delete();

        return Redirect::back()->with('success', "Le produit a bien été supprimé");
    }
}
