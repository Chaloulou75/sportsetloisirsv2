<?php

namespace App\Http\Controllers;

use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Http\Request;

class CategoryDisciplineCritereController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritere $lienDisciplineCategorieCritere)
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discCatCritere = LienDisciplineCategorieCritere::where('id', $lienDisciplineCategorieCritere->id)->firstOrFail();
        dd($discCatCritere);

        $discCatCritere->delete();
        return redirect()->back()->with('success', 'Critère supprimé');

    }
}
