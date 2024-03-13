<?php

namespace App\Http\Controllers;

use App\Models\ProductReservation;
use App\Models\StructurePlanning;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationPlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function update(Request $request, ProductReservation $reservation, StructurePlanning $planning)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReservation $reservation, StructurePlanning $planning): RedirectResponse
    {
        $reservation->plannings()->detach($planning);

        return to_route('panier.index')->with('success', 'Créneau supprimé de votre panier');

    }
}
