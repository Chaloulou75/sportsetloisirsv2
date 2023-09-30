<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
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
    public function index(Request $request)
    {
        $favoriteProduitsCookies = $request->cookie('favoriteProduits');
        $favoriteProduitsIds = json_decode($favoriteProduitsCookies);
        if($favoriteProduitsIds !== null) {
            $produits = StructureProduit::with([
            'structure:id,name,slug,structuretype_id,address,address_lat,address_lng,zip_code,city_id,city,departement_id,website,view_count',
            'adresse',
            'discipline:id,name,slug,view_count',
            'categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            'activite:id,discipline_id,categorie_id,structure_id,titre,description,image,actif',
            'activite.discipline:id,name,slug',
            'activite.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            'criteres:id,activite_id,produit_id,critere_id,valeur',
            'criteres.critere:id,nom',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
            ])
            ->whereIn('id', $favoriteProduitsIds)
            ->get();
        }

        $favoriteActivitiesCookies = $request->cookie('favoriteActivities');
        $favoriteActivitiesIds = json_decode($favoriteActivitiesCookies);
        if($favoriteActivitiesIds !== null) {
            $activites = StructureActivite::with([
            'structure',
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.plannings',
            ])
            ->whereIn('id', $favoriteActivitiesIds)
            ->get();
        }

        $favoriteStructuresCookies = $request->cookie('favoriteStructures');
        $favoriteStructuresIds = json_decode($favoriteStructuresCookies);
        if($favoriteStructuresIds !== null) {
            $structures = Structure::with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    'disciplines',
                    'disciplines.discipline:id,name,slug',
                    'categories',
                    'activites',
                    'activites.discipline',
                    'activites.categorie',
                    'produits',
                    'produits.criteres',
                    'tarifs',
                    'tarifs.tarifType',
                    'tarifs.structureTarifTypeInfos',
                    'plannings',
                    ])->whereIn('id', $favoriteStructuresIds)
                    ->get();
        }

        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                                ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                                ->get();

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
