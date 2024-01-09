<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DisciplineStructuretypeActiviteController extends Controller
{
    public function show(ListDiscipline $discipline, $structuretype, $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::where('id', request()->produit)->first();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();


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


        $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();

        $activite = StructureActivite::withRelations()->find($activite);

        $produits = $activite->produits;

        $logoUrl = asset($activite->structure->logo);

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
                ->whereIn('discipline_id', $activite->structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $activite->structure->categories->pluck('categorie_id'))
                ->get();

        $activiteSimilaires = StructureActivite::withRelations()->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
            'discipline' => $requestDiscipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'produits' => $produits,
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'logoUrl' => $logoUrl,
            'activite' => $activite,
            'criteres' => $criteres,
            'activiteSimilaires' => $activiteSimilaires,
            'selectedProduit' => $selectedProduit,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'structuretypeElected' => $structuretypeElected,
        ]);
    }
}
