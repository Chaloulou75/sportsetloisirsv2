<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DisciplineStructuretypeStructureController extends Controller
{
    /**
      * Display the specified resource.
      */
    public function show(ListDiscipline $discipline, $structuretype, $structure)
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $structure = Structure::withRelations()
                            ->where('slug', $structure)
                            ->first();

        $logoUrl = asset($structure->logo);

        $requestDiscipline = ListDiscipline::with('structureProduits')->where('slug', $discipline->slug)
                                    ->select(['id', 'name', 'slug', 'view_count'])
                                    ->first();

        $disciplinesSimilaires = $requestDiscipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                ->where('discipline_id', $requestDiscipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->withCount('structures_produits')
                ->orderByDesc('structures_produits_count')
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline) {
                $subquery->where('discipline_id', $requestDiscipline->id);
            });
        })
        ->select(['id', 'name', 'slug'])
        ->get();

        $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype);

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))
                ->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
                ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => $structure,
            'familles' => $familles,
            'allCities' => $allCities,
            'listDisciplines' => $listDisciplines,
            'criteres' => $criteres,
            'logoUrl' => $logoUrl,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'structuretypeElected' => $structuretypeElected,
            'requestDiscipline' => $requestDiscipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
        ]);
    }
}
