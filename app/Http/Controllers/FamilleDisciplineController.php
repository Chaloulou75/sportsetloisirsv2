<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Http\RedirectResponse;

class FamilleDisciplineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $familleNotInId = $request->input('familleNotIn');
        $familleNotIn = Famille::findOrFail($familleNotInId);
        $discipline->familles()->attach($familleNotIn);

        return to_route('admin.disciplines.familles.edit', $discipline)->with('success', 'Famille ajoutée');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);
        $disciplineFamillesIds = $discipline->familles()->select('famille_id')
                    ->pluck('famille_id');
        $familles = Famille::select('id', 'name', 'slug', 'nom_long')->whereNotIn('id', $disciplineFamillesIds)->get();

        return Inertia::render('Admin/Disciplines/Familles/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'discipline' => $discipline,
            'familles' => $familles,
        ]);
    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $familleInId = $request->input('familleIn');
        $familleIn = Famille::findOrFail($familleInId);
        $discipline->familles()->detach($familleIn);

        return to_route('admin.disciplines.familles.edit', $discipline)->with('success', 'Famille supprimée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
