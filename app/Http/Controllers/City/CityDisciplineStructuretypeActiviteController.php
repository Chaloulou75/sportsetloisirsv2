<?php

namespace App\Http\Controllers\City;

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
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityDisciplineStructuretypeActiviteController extends Controller
{
    public function show(City $city, ListDiscipline $discipline, StructureType $structuretype, StructureActivite $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::withRelations()->find(request()->produit);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $city = City::with(['structures', 'produits.adresse'])
                            ->withProductsAndDepartement()
                            ->withCount('produits')
                            ->withCount('structures')
                            ->find($city->id);

        $citiesAround = City::with('produits')->withCitiesAround($city)->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city, $cityAroundIds) {
            $query->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
        })
            ->where('discipline_id', $requestDiscipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $requestDiscipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
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
            ])
            ->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
            'produits' => fn () => $produits,
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => CityResource::collection($allCities),
            'activite' => fn () => $activite,
            'criteres' => fn () => $criteres,
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'discipline' => fn () => $requestDiscipline,
            'structuretypeElected' => fn () => $structuretypeElected,
            'activiteSimilaires' => fn () => $activiteSimilaires,
            'selectedProduit' => fn () => $selectedProduit,
            'categories' => fn () => $categories,
            'firstCategories' => fn () => $firstCategories,
            'categoriesNotInFirst' => fn () => $categoriesNotInFirst,
            'allStructureTypes' => fn () => $allStructureTypes,
        ]);
    }
}
