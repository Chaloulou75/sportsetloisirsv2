<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;

class AdminCategorieDisciplineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Categorie $categorie): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        // $categorie->disciplines()->detach();

        $disciplineIds = ListDiscipline::pluck('id')->toArray();

        foreach ($disciplineIds as $disciplineId) {
            if (!$categorie->disciplines->contains($disciplineId)) {
                $timestamp = now()->timestamp;
                $randomComponent = mt_rand(0, 99999);
                $slug = Str::slug($categorie->nom . '-' . $timestamp . '-' . $randomComponent);

                $categorie->disciplines()->attach($disciplineId, [
                    'slug' => $slug,
                    'nom_categorie_client' => $categorie->nom,
                    'nom_categorie_pro' => $categorie->nom,
                ]);

                $attached = LienDisciplineCategorie::where('discipline_id', $disciplineId)->where('categorie_id', $categorie->id)->first();

                $attached->update(['slug' => $categorie->nom . '-' . $attached->id]);
            }
        }

        return to_route('admin.categories.index')->with('success', 'Catégorie liée à toutes les disciplines!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorie->disciplines()->detach();
        return to_route('admin.categories.index')->with('success', 'Catégorie déliée à toutes ses disciplines!');
    }
}
