<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Redirect;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

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
        $request->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'categorie_id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'activite_id' => ['required', Rule::exists('structures_activites', 'id')],
            'event' => 'required',
        ]);

        $dateStart = $request->event['start'];
        $startDate = Carbon::parse($dateStart)->setTimezone('Europe/Paris')->toDateTimeString();

        $dateEnd = $request->event['end'];
        $endDate = Carbon::parse($dateEnd)->setTimezone('Europe/Paris')->toDateTimeString();

        $produit = StructureProduit::create([
            'structure_id' => $request->structure_id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'activite_id' => $request->activite_id,
            'actif' => 1,
            'lieu_id' => $structure->adresses->first()->id,
            'reservable' => 0,
        ]);

        $criteres = LienDisciplineCategorieCritere::where('discipline_id', $request->discipline_id)->where('categorie_id', $request->categorie_id)->get();

        foreach ($criteres as $key => $critereValue) {
            $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $key)->first();

            if (isset($critereValue)) {
                $structureProduitCriteres = StructureProduitCritere::create([
                    'structure_id' => $structure->id,
                    'discipline_id' => $request->discipline_id,
                    'categorie_id' => $request->categorie_id,
                    'activite_id' => $request->activite_id,
                    'produit_id' => $produit->id,
                    'critere_id' => $key,
                    'valeur' => $defaut->valeur,
                ]);
            }
        }

        StructurePlanning::create([
            'structure_id' => $request->structure_id,
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'activite_id' => $request->activite_id,
            'produit_id' => $produit->id,
            'title' => $request->event['title'] ?? "",
            'start' => $startDate ?? "",
            'end' => $endDate ?? "",
        ]);

        return Redirect::back()->with('success', "Le produit a bien été ajouté au planning");

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
        $planning = StructurePlanning::findOrFail($event);

        $produit = StructureProduit::find($planning->produit_id);

        $produitCriteres = StructureProduitCritere::where('produit_id', $planning->produit_id)->get();

        $planning->delete();

        if(isset($produitsCriteres)) {
            foreach($produitCriteres as $critere) {
                $critere->delete();
            }
        }

        if(isset($produit->tarifs)) {
            $produit->tarifs()->detach();
        }

        $produit->delete();

        return Redirect::back()->with('success', "Le produit a bien été supprimé");
    }
}
