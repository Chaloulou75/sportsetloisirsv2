<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PanierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $sessionReservations = $this->getSessionReservations();

        if(auth()->user()) {
            $reservations = auth()->user()->productReservations()->withRelations()->get();
        }

        $produitsDesired = StructureProduit::withRelations()
        ->whereIn('structures_produits.id', $sessionReservations->pluck('produit_id'))
        ->get();

        return Inertia::render('Panier/Index', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'produitsDesired' => $produitsDesired,
            'reservations' => $reservations ?? null
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

    private function getSessionReservations(): Collection
    {
        $sessionReservations = collect();
        $sessionProducts = session()->get('panierProducts', []);

        foreach ($sessionProducts as $panierProduct) {
            $sessionReservations->push([
                'user_id' => null,
                'produit_id' => $panierProduct['produit_id'],
                'tarif_id' => $panierProduct['tarif_id'],
            ]);
        }

        return $sessionReservations;
    }
}