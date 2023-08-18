<?php

namespace App\Http\Controllers;

use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;
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

        $discCatCritere = LienDisciplineCategorieCritere::with('valeurs')->where('id', $lienDisciplineCategorieCritere->id)->firstOrFail();

        if($discCatCritere->valeurs->isNotEmpty()) {
            foreach ($discCatCritere->valeurs as $valeur) {
                $valeur->delete();
            }
        }

        $discCatCritere->delete();
        return redirect()->back()->with('success', 'Critère et valeurs associés supprimés');

    }
}
