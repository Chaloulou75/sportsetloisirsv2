<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;

class FamilleDisciplineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $familleNotInId = $request->input('familleNotIn');
        $familleNotIn = Famille::findOrFail($familleNotInId);
        $discipline->familles()->attach($familleNotIn);
        return to_route('admin.edit', $discipline)->with('success', 'Famille ajoutée');
    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);
        $familleInId = $request->input('familleIn');
        $familleIn = Famille::findOrFail($familleInId);
        $discipline->familles()->detach($familleIn);
        return to_route('admin.edit', $discipline)->with('success', 'Famille supprimée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
