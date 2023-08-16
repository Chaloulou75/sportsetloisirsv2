<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListDiscipline;

class DisciplineSimilaireController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $disciplineNotInId = $request->input('disciplineNotIn');
        $disciplineNotIn = ListDiscipline::findOrFail($disciplineNotInId);
        $discipline->disciplinesSimilaires()->attach($disciplineNotIn);
        return redirect()->back()->with('success', 'Discipline similaire ajoutée');
    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);
        $disciplineInId = $request->input('disciplineIn');
        $disciplineIn = ListDiscipline::findOrFail($disciplineInId);
        $discipline->disciplinesSimilaires()->detach($disciplineIn);
        return redirect()->back()->with('success', 'Discipline similaire supprimée');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ListDiscipline $discipline)
    {
        //
    }
}
