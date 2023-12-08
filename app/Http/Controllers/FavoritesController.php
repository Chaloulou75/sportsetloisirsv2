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

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

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
                    'familles' => $familles,
                    'structures' => $structures ?? [],
                    'activites' => $activites ?? [],
                    'produits' => $produits ?? [],
                    'allCities' => $allCities,
                    'listDisciplines' => $listDisciplines,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
