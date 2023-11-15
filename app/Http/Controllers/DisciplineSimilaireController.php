<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
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

        return to_route('admin.disciplines.similaires.edit', $discipline)->with('success', 'Discipline similaire ajoutée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
                    ->select('discipline_similaire_id', 'name', 'slug', 'famille')
                    ->get();

        $disciplinesSimilairesIds = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id')
            ->pluck('discipline_similaire_id');

        $listDisciplines = ListDiscipline::select(['id', 'slug', 'name'])->whereNotIn('id', $disciplinesSimilairesIds)->whereNot('id', $discipline->id)->paginate(12);

        return Inertia::render('Admin/Disciplines/Similaires/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'discipline' => $discipline,
            'listDisciplines' => $listDisciplines,
            'disciplinesSimilaires' => $disciplinesSimilaires,
        ]);
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

        return to_route('admin.disciplines.similaires.edit', $discipline)->with('success', 'Discipline similaire supprimée');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ListDiscipline $discipline)
    {
        //
    }
}
