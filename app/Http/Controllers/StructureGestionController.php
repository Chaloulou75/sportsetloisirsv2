<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Auth;

class StructureGestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure)
    {
        $allReservations = ProductReservation::with([
            'user',
            'produit',
            'produit.criteres',
            'produit.criteres.critere',
            'produit.activite',
            'tarif',
            'tarif.tarifType',
            'tarif.structureTarifTypeInfos',
            'tarif.structureTarifTypeInfos.tarifTypeAttribut',
            'planning'
        ])->get();

        $allReservationsCount = $allReservations->count();

        $pendingReservations = ProductReservation::with([
                'user',
                'produit',
                'produit.criteres',
                'produit.criteres.critere',
                'produit.activite',
                'tarif',
                'tarif.tarifType',
                'tarif.structureTarifTypeInfos',
                'tarif.structureTarifTypeInfos.tarifTypeAttribut',
                'planning'
            ])
            ->where('pending', true)
            ->get();

        $pendingReservationsCount = $pendingReservations->count();

        $confirmedReservations = ProductReservation::with([
                'user',
                'produit',
                'produit.criteres',
                'produit.criteres.critere',
                'produit.activite',
                'tarif',
                'tarif.tarifType',
                'tarif.structureTarifTypeInfos',
                'tarif.structureTarifTypeInfos.tarifTypeAttribut',
                'planning'
            ])
            ->where('confirmed', true)
            ->get();

        $confirmedReservationsCount = $confirmedReservations->count();

        $structure = Structure::with([
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'creator:id,name,email',
                    'users:id,name',
                    'cities:id,ville,ville_formatee',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    'disciplines',
                    'disciplines.discipline:id,name,slug',
                    'categories',
                    'activites',
                    'activites.discipline',
                    'activites.categorie',
                    'activites.produits',
                    'activites.produits.adresse',
                    'activites.produits.criteres',
                    'activites.produits.criteres.critere',
                    'activites.produits.tarifs',
                    'activites.produits.tarifs.tarifType',
                    'activites.produits.tarifs.structureTarifTypeInfos',
                    'activites.produits.plannings',
                    ])
                    ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'abo_news', 'abo_promo', 'logo'])
                    ->where('slug', $structure->slug)
                    ->firstOrFail();


        return Inertia::render('Structures/Gestion/Index', [
            'structure' => $structure,
            'allReservations' => $allReservations,
            'allReservationsCount' => $allReservationsCount,
            'confirmedReservations' => $confirmedReservations,
            'confirmedReservationsCount' => $confirmedReservationsCount,
            'pendingReservations' => $pendingReservations,
            'pendingReservationsCount' => $pendingReservationsCount,

            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
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
