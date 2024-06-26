<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\ListDisciplineResource;
use App\Http\Resources\StructureActiviteResource;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureResource;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $favoriteProduitsCookies = $request->cookie('favoriteProduits');
        $favoriteProduitsIds = json_decode($favoriteProduitsCookies);
        if($favoriteProduitsIds !== null) {
            $produits = StructureProduit::withRelations()
            ->whereIn('id', $favoriteProduitsIds)
            ->get();
        }

        $favoriteActivitiesCookies = $request->cookie('favoriteActivities');
        $favoriteActivitiesIds = json_decode($favoriteActivitiesCookies);
        if($favoriteActivitiesIds !== null) {
            $activites = StructureActivite::withRelations()
            ->whereIn('id', $favoriteActivitiesIds)
            ->get();
        }

        $favoriteStructuresCookies = $request->cookie('favoriteStructures');
        $favoriteStructuresIds = json_decode($favoriteStructuresCookies);
        if($favoriteStructuresIds !== null) {
            $structures = Structure::withRelations()->whereIn('id', $favoriteStructuresIds)
                    ->get();
        }

        return Inertia::render('Favorites/Index', [
            'familles' => fn () => FamilleResource::collection($familles),
            'structures' => fn () => StructureResource::collection($structures ?? []),
            'activites' => fn () => StructureActiviteResource::collection($activites ?? []),
            'produits' => fn () => StructureProduitResource::collection($produits ?? []),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
        ]);

    }
}
