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
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;
use App\Http\Resources\StructureActiviteResource;

class CityActiviteController extends Controller
{
    public function show(City $city, StructureActivite $activite, string $slug, ?string $produit = null): Response
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

        $city = City::with(['structures', 'produits.adresse'])
                    ->withProductsAndDepartement()
                    ->withCount('produits')
                    ->withCount('structures')
                    ->find($city->id);

        $citiesAround = City::withCitiesAround($city)->get();

        $activite = StructureActivite::with([
            'structure',
            'structure.adresses',
            'instructeurs'
        ])->find($activite->id);

        $produits = $activite->produits()->withRelations()->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $activite->discipline_id)
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
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'allCities' => fn () => CityResource::collection($allCities),
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'activite' => fn () => StructureActiviteResource::make($activite) ,
            'activiteSimilaires' => fn () => StructureActiviteResource::collection($activiteSimilaires),
            'selectedProduit' => fn () => $selectedProduit ?? null,
            'produits' => fn () => StructureProduitResource::collection($produits),
        ]);
    }
}
