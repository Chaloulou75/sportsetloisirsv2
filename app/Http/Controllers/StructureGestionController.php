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
        $structure = Structure::with(['activites' => function ($query) {
            $query->with(['reservations'])
                  ->withCount('reservations')
                  ->orderByDesc('reservations_count')
                  ->take(3);
        }])->findOrFail($structure->id);


        $structureNotifs = $structure->unreadNotifications;

        $allReservationsQuery = $structure->reservations()
                        ->with(['plannings', 'cat_tarif.cat_tarif_type', 'user'])
                        ->withCount('plannings')
                        ->where('paid', true)
                        ->latest()
                        ->get();

        $allReservationsCount = $allReservationsQuery->count();

        $pendingReservationsAll = $allReservationsQuery->where('pending', true);
        $pendingReservationsCount = $pendingReservationsAll->count();
        $totalAmountPending = $pendingReservationsAll->sum(function ($reservation) {
            return $this->calculateTotalPrice($reservation);
        });
        $pendingReservations = $pendingReservationsAll->paginate(10);

        $confirmedReservationsAll = $allReservationsQuery->where('confirmed', true);
        $totalAmountConfirmed = $confirmedReservationsAll->sum(function ($reservation) {
            return $this->calculateTotalPrice($reservation);
        });
        $confirmedReservations = $confirmedReservationsAll->paginate(10);
        $confirmedReservationsCount = $confirmedReservations->total();

        return Inertia::render('Structures/Gestion/Index', [
            'structure' => fn () => $structure,
            'structureNotifs' => fn () => $structureNotifs,
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
