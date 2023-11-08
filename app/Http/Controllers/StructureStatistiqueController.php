<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Auth;

class StructureStatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure)
    {

        $allReservations = ProductReservation::withRelations()
                ->get();

        $allReservationsCount = $allReservations->count();

        $pendingReservations = ProductReservation::withRelations()
            ->where('pending', true)
            ->get();

        $pendingReservationsCount = $pendingReservations->count();

        $confirmedReservations = ProductReservation::withRelations()
            ->where('confirmed', true)
            ->get();

        $confirmedReservationsCount = $confirmedReservations->count();

        $totalAmountConfirmed = $confirmedReservations->sum(function ($reservation) {
            return $reservation->tarif->amount;
        });

        $totalAmountPending = $pendingReservations->sum(function ($reservation) {
            return $reservation->tarif->amount;
        });

        $structure = Structure::withRelations()
                    ->where('slug', $structure->slug)
                    ->firstOrFail();

        return Inertia::render('Structures/Gestion/Statistiques/Index', [
            'structure' => $structure,
            'allReservations' => $allReservations,
            'allReservationsCount' => $allReservationsCount,
            'confirmedReservations' => $confirmedReservations,
            'confirmedReservationsCount' => $confirmedReservationsCount,
            'pendingReservations' => $pendingReservations,
            'pendingReservationsCount' => $pendingReservationsCount,
            'totalAmountPending' => $totalAmountPending,
            'totalAmountConfirmed' => $totalAmountConfirmed,
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
