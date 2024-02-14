<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;

class StructurePlanningMultipleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure)
    {
        $request->validate([
            'title' => ['nullable', 'string'],
            'activite_id' => ['required', Rule::exists(StructureActivite::class, 'id')],
            'produit_id' => ['required', Rule::exists(StructureProduit::class, 'id')],
            'horaires' => ['required'],
            'dates' => ['required'],
        ]);
        dd($request->all());

        $activite = StructureActivite::findOrFail($request->activite);
        $produit = StructureProduit::findOrFail($request->produit);
        $horaires = array_map(function ($horaire) {
            return Carbon::createFromTime($horaire['hours'], $horaire['minutes'])->format('H:i');
        }, $request->horaires);

        $dates = array_map(function ($date) {
            return Carbon::parse($date)->setTimezone('Europe/Paris')->format('Y-m-d');
        }, $request->dates);

        dd($dates, $horaires);
        // Create Carbon instances
        $startDate = createCarbonInstance($dates[0], 'j F Y');
        $endDate = createCarbonInstance($dates[1], 'j F Y');
        $startTime = Carbon::createFromFormat('H\hi', $horaires[0]);
        $endTime = Carbon::createFromFormat('H\hi', $horaires[1]);

        $combinedDatePairs = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $startDateTime = $currentDate->copy()->setTime($startTime->hour, $startTime->minute);
            $endDateTime = $currentDate->copy()->setTime($endTime->hour, $endTime->minute);

            $combinedDatePairs[] = [
                'start' => $startDateTime->toDateTimeString(),
                'end' => $endDateTime->toDateTimeString(),
            ];

            $currentDate->addDay();
        }
        dd($combinedDatePairs);
        foreach ($combinedDatePairs as $combinedDatePair) {
            StructurePlanning::create([
                'structure_id' => $structure->id,
                'discipline_id' => $activite->discipline_id,
                'categorie_id' => $activite->categorie_id,
                'activite_id' => $activite->id,
                'produit_id' => $produit->id,
                'title' => $activite->titre,
                'start' => $combinedDatePair['start'] ?? "",
                'end' => $combinedDatePair['end'] ?? "",
            ]);
        }

        return to_route('structures.disciplines.show', ['structure' => $structure, 'discipline' => $activite->discipline])->with('success', "L'évènement a bien été ajouté au planning");

    }
}
