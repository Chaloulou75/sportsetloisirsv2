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
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityDisciplineCategorieActiviteController extends Controller
{
    public function show(City $city, $discipline, $category, $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::where('id', request()->produit)->first();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $city = City::with(['structures', 'produits', 'produits.adresse'])
                            ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                            ->where('slug', $city->slug)
                            ->withCount('produits')
                            ->first();

        $citiesAround = City::with('produits')->withCitiesAround($city)->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->where('slug', $discipline)
        ->first();


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

        $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)->where('slug', $category)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();

        $activite = StructureActivite::withRelations()->find($activite);

        $produits = $activite->produits;

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
            'produits' => $produits,
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'activite' => $activite,
            'criteres' => $criteres,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'activiteSimilaires' => $activiteSimilaires,
            'discipline' => $requestDiscipline,
            'requestCategory' => $requestCategory,
            'selectedProduit' => $selectedProduit,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
        ]);
    }
}
