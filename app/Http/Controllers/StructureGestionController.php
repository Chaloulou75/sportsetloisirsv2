<?php

namespace App\Http\Controllers;

use App\Http\Resources\StructureResource;
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
            'structure' => fn () => StructureResource::make($structure),
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

    public function markAsRead(Structure $structure, $notification)
    {
        $notif = $structure->unreadNotifications()->find($notification);

        if($notif) {
            $notif->markAsRead();
        }

        return to_route('structures.gestion.index', $structure)->with('message', 'Notification marquée comme lue.');
    }
}
