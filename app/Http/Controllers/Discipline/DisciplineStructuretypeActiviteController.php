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
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\StructureActiviteResource;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;
use App\Http\Resources\StructureProduitResource;

class DisciplineStructuretypeActiviteController extends Controller
{
    public function show(ListDiscipline $discipline, Structuretype $structuretype, StructureActivite $activite, string $slug, ?string $produit = null): Response
    {

        if ($produit !== null) {
            $selectedProduit = StructureProduitResource::make(StructureProduit::withRelations()->find($produit));
        }


        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

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

        $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype->id);

        $activite = StructureActivite::with([
            'structure',
            'structure.adresses',
            'instructeurs'
        ])->find($activite->id);

        $produits = $activite->produits()->withRelations()->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $requestDiscipline->id)
                ->where('categorie_id', $activite->categorie_id)
                ->where('visible_front', true)
                ->get();

        $activiteSimilaires = StructureActivite::with([
                'produits',
                'produits.criteres',
                'produits.criteres.sous_criteres',
                'produits.adresse'
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
            'selectedProduit' => fn () => $selectedProduit ?? null,
            'discipline' => fn () => ListDisciplineResource::make($requestDiscipline),
            'produits' => fn () => StructureProduitResource::collection($produits),
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'allCities' => fn () => CityResource::collection($allCities),
            'activite' => fn () => StructureActiviteResource::make($activite),
            'activiteSimilaires' => fn () => StructureActiviteResource::collection($activiteSimilaires),

            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories),
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'structuretypeElected' => fn () => StructuretypeResource::make($structuretypeElected),
        ]);
    }
}
