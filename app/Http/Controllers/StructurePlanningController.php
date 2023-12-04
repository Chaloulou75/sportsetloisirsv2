<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;

class StructurePlanningController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Structure $structure, Request $request)
    {
        $request->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'title' => ['nullable', 'string'],
            'activite_id' => ['required', Rule::exists('structures_activites', 'id')],
            'produit_id' => ['required', Rule::exists('structures_produits', 'id')],
            'event' => ['required'],
        ]);

        $activite = StructureActivite::findOrFail($request->activite_id);

        $dateStart = $request->event['start'];
        $startDate = Carbon::parse($dateStart)->setTimezone('Europe/Paris')->toDateTimeString();

        $dateEnd = $request->event['end'];
        $endDate = Carbon::parse($dateEnd)->setTimezone('Europe/Paris')->toDateTimeString();

        $produit = StructureProduit::findOrFail($request->produit_id);

        StructurePlanning::create([
            'structure_id' => $request->structure_id,
            'discipline_id' => $activite->discipline_id,
            'categorie_id' => $activite->categorie_id,
            'activite_id' => $activite->id,
            'produit_id' => $produit->id,
            'title' => $request->title ?? $activite->titre,
            'start' => $startDate ?? "",
            'end' => $endDate ?? "",
        ]);

        return to_route('structures.disciplines.show', ['structure' => $structure, 'discipline' => $activite->discipline])->with('success', "L'évènement a bien été ajouté au planning");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, $event)
    {
        $request->validate([
            'event' => 'required',
        ]);

        $planning = StructurePlanning::findOrFail($event);

        $activite = StructureActivite::findOrFail($planning->activite_id);

        $title = $request->event['title'];

        $dateStart = $request->event['start'];
        $startDate = Carbon::parse($dateStart)->setTimezone('Europe/Paris')->toDateTimeString();

        $dateEnd = $request->event['end'];
        $endDate = Carbon::parse($dateEnd)->setTimezone('Europe/Paris')->toDateTimeString();

        // $activite->update([
        //     'titre' => $title,
        // ]);

        $planning->update([
            'title' => $title,
            'start' => $startDate,
            'end' => $endDate,
        ]);

        return to_route('structures.disciplines.index', $structure)->with('success', "Planning mis à jour");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $event)
    {
        $planning = StructurePlanning::findOrFail($event);

        $planning->delete();

        return to_route('structures.disciplines.index', $structure)->with('success', "L'évenement a bien été supprimé");
    }
}
