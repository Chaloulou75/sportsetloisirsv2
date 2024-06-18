<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\ListDisciplineResource;

class DisciplineSimilaireController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $disciplineNotInId = $request->input('disciplineNotIn');
        $disciplineNotIn = ListDiscipline::findOrFail($disciplineNotInId);
        $discipline->disciplines_similaires()->attach($disciplineNotIn);

        return to_route('admin.disciplines.similaires.edit', $discipline)->with('success', 'Discipline similaire ajoutée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        $disciplinesSimilaires = $discipline->disciplines_similaires()
                    ->select('discipline_similaire_id', 'name', 'slug', 'famille')
                    ->get();

        $disciplinesSimilairesIds = $discipline->disciplines_similaires()
            ->select('discipline_similaire_id')
            ->pluck('discipline_similaire_id');

        $listDisciplines = ListDiscipline::select(['id', 'slug', 'name'])->whereNotIn('id', $disciplinesSimilairesIds)->whereNot('id', $discipline->id)->paginate(12);

        return Inertia::render('Admin/Disciplines/Similaires/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'disciplinesSimilaires' => fn () => ListDisciplineResource::collection($disciplinesSimilaires),
        ]);
    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);
        $disciplineInId = $request->input('disciplineIn');
        $disciplineIn = ListDiscipline::findOrFail($disciplineInId);
        $discipline->disciplines_similaires()->detach($disciplineIn);

        return to_route('admin.disciplines.similaires.edit', $discipline)->with('success', 'Discipline similaire supprimée');
    }

}
