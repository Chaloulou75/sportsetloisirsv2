<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\LienDisciplineCategorieCritere;

class CityActiviteController extends Controller
{
    public function show(City $city, $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::find(request()->produit);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $city = City::with(['structures', 'produits.adresse'])
                    ->withProductsAndDepartement()
                    ->where('slug', $city->slug)
                    ->withCount('produits')
                    ->withCount('structures')
                    ->first();

        $citiesAround = City::withCitiesAround($city)->get();

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
            'selectedProduit' => $selectedProduit,
            'produits' => $produits,
        ]);
    }
}
