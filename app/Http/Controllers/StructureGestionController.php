<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Auth;

class StructureGestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure): Response
    {
        $structure = Structure::withRelations()->findOrFail($structure->id);
        $structureNotifs = $structure->unreadNotifications;

        $allReservations = ProductReservation::withRelations()
                        ->withCount('plannings')
                        ->where('structure_id', $structure->id)
                        ->where('paid', true)
                        ->paginate(10);
        $allReservationsCount = $allReservations->total();

        $pendingReservations = ProductReservation::withRelations()
            ->withCount('plannings')
            ->where('structure_id', $structure->id)
            ->where('paid', true)
            ->where('pending', true)
            ->latest()
            ->paginate(10);

        $pendingReservationsAll = ProductReservation::withRelations()
                    ->withCount('plannings')
                    ->where('structure_id', $structure->id)
                    ->where('paid', true)
                    ->where('pending', true)
                    ->get();

        $pendingReservationsCount = $pendingReservations->total();

        $totalAmountPending = $pendingReservationsAll->sum(function ($reservation) {
            return $this->calculateTotalPrice($reservation);
        });

        $confirmedReservations = ProductReservation::withRelations()
            ->withCount('plannings')
            ->where('structure_id', $structure->id)
            ->where('paid', true)
            ->where('confirmed', true)
            ->latest()
            ->paginate(10);
        $confirmedReservationsCount = $confirmedReservations->total();

        $confirmedReservationsAll = ProductReservation::withRelations()
                    ->withCount('plannings')
                    ->where('structure_id', $structure->id)
                    ->where('paid', true)
                    ->where('confirmed', true)
                    ->get();

        $totalAmountConfirmed = $confirmedReservationsAll->sum(function ($reservation) {
            return $this->calculateTotalPrice($reservation);
        });


        return Inertia::render('Structures/Gestion/Index', [
            'structure' => fn () => $structure,
            'structureNotifs' => fn () => $structureNotifs,
            'allReservations' => fn () => $allReservations,
            'allReservationsCount' => fn () => $allReservationsCount,
            'confirmedReservations' => fn () => $confirmedReservations,
            'confirmedReservationsCount' => fn () => $confirmedReservationsCount,
            'pendingReservations' => fn () => $pendingReservations,
            'pendingReservationsCount' => fn () => $pendingReservationsCount,
            'totalAmountPending' => fn () => $totalAmountPending,
            'totalAmountConfirmed' => fn () => $totalAmountConfirmed,
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

    private function calculateTotalPrice($reservation)
    {
        $totalPrice = 0;
        if ($reservation->plannings_count > 0) {
            foreach ($reservation->plannings as $planning) {
                $price = $planning->pivot->quantity * $reservation->tarif_amount;
                $totalPrice += $price;
            }
        } else {
            $totalPrice += $reservation->tarif_amount * $reservation->quantity;
        }
        return $totalPrice;
    }
}
