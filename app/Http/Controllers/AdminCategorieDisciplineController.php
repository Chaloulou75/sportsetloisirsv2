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
