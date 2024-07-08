<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use Illuminate\Http\RedirectResponse;

class StructurePlanningMultipleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure): RedirectResponse
    {
        // dd($request->all());
        $request->validate([
            'title' => ['nullable', 'string'],
            'activite' => ['required', Rule::exists(StructureActivite::class, 'id')],
            'produit' => ['required', Rule::exists(StructureProduit::class, 'id')],
            'horaires' => 'required|array',
            'dates' => 'required|array',
        ]);

        $activite = StructureActivite::findOrFail($request->activite);
        $produit = StructureProduit::findOrFail($request->produit);

        $horaires = array_map(function ($datetime) {
            $carbonDate = Carbon::parse($datetime);
            return $carbonDate->setTimezone('Europe/Paris')->format('H:i');
        }, array_values($request->horaires));

        $dates = array_map(function ($date) {
            return Carbon::parse($date)->setTimezone('Europe/Paris')->format('Y-m-d');
        }, $request->dates);

        $startDate = Carbon::createFromFormat('Y-m-d', $dates[0]);
        $endDate = Carbon::createFromFormat('Y-m-d', $dates[1]);
        $startTime = Carbon::createFromFormat('H:i', $horaires[0]);
        $endTime = Carbon::createFromFormat('H:i', $horaires[1]);

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
