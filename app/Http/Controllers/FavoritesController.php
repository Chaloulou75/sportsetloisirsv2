<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\StructureActivite;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $favoriteActivitiesCookies = $request->cookie('favoriteActivities');
        $favoriteActivitiesCookies = json_decode($favoriteActivitiesCookies);
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
            ->whereIn('id', $favoriteActivitiesCookies)
            ->get();

        $favoriteStructuresCookies = $request->cookie('favoriteStructures');
        $favoriteStructuresCookies = json_decode($favoriteStructuresCookies);
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
                    ])->whereIn('id', $favoriteStructuresCookies)
                    ->get();

        $familles = Famille::with([
                    'disciplines' => function ($query) {
                        $query->whereHas('structures');
                    }
                ])
                ->whereHas('disciplines', function ($query) {
                    $query->whereHas('structures');
                })->select(['id', 'name', 'slug'])->get();


        return Inertia::render('Favorites/Index', [
                    'familles' => $familles,
                    'structures'=> $structures,
                    'activites' => $activites,
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
