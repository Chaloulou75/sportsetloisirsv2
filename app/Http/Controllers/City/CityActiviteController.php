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
    public function show(City $city, StructureActivite $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::withRelations()->find(request()->produit);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $city = City::with(['structures', 'produits.adresse'])
                    ->withProductsAndDepartement()
                    ->withCount('produits')
                    ->withCount('structures')
                    ->find($city->id);

        $citiesAround = City::withCitiesAround($city)->get();

        $activite = StructureActivite::withRelations()->find($activite->id);

        $produits = $activite->produits()->withRelations()->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $activite->discipline_id)
                ->where('categorie_id', $activite->categorie_id)
                ->where('visible_front', true)
                ->where('visible_block', true)
                ->get();

        $activiteSimilaires = StructureActivite::withRelations()
            ->whereNot('id', $activite->id)
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
