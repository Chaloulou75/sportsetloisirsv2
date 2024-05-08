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

    public function duplicate(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validatedData = $request->validate([
            'discipline_origin' => ['required', 'exists:liste_disciplines,slug'],
            'discipline_target' => ['required', 'exists:liste_disciplines,slug'],
        ]);

        $dis_origin = ListDiscipline::with('categories')->where('slug', $validatedData['discipline_origin'])->firstOrFail();
        $dis_target = ListDiscipline::with('categories')->where('slug', $validatedData['discipline_target'])->firstOrFail();

        foreach ($dis_origin->categories as $category) {
            $originPivotAttributes = $category->pivot;
            $targetCategoriesWithPivot = $dis_target->categories;

            // Check if the target discipline's categories contain the same categorie_id as the origin category
            $categoryExistsInTarget = $targetCategoriesWithPivot->contains(function ($targetCategory) use ($originPivotAttributes) {
                return $targetCategory->pivot->categorie_id === $originPivotAttributes->categorie_id;
            });

            if (!$categoryExistsInTarget) {

                $pivotAttributes = collect($originPivotAttributes)->except(['id','discipline_id', 'categorie_id'])->toArray();

                $pivotAttributes['slug'] = Str::slug($category->nom) . '-' . $category->id . '_random_texte';

                $dis_target->categories()->attach($category->id, $pivotAttributes);

                $pivot = $dis_target->categories()->where('categorie_id', $category->id)->first()->pivot;

                if ($pivot) {
                    $newSlug = Str::slug($category->nom) . '-' . $pivot->id;
                    $dis_target->categories()->updateExistingPivot($category->id, ['slug' => $newSlug]);
                }
            }
        }

        return to_route('admin.disciplines.index')->with('success', 'Les catégories de la discipline '. $dis_origin->name .' a été dupliquée à '. $dis_target->name .'.');
    }
}
