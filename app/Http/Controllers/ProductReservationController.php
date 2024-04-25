<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Mail\ReservationAsked;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureCatTarif;
use App\Models\StructurePlanning;
use App\Mail\ReservationConfirmed;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;

class ProductReservationController extends Controller
{
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
                        ->with([
                            'plannings',
                            'attributs',
                            'attributs.booking_field',
                            'attributs.reservation_sous_attributs',
                            'attributs.reservation_sous_attributs.booking_sous_field', 'cat_tarif.cat_tarif_type',
                            'user'
                        ])
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

        $finishedReservationsAll = $allReservationsQuery->where('finished', true);
        $finishedReservations = $finishedReservationsAll->paginate(10);
        $finishedReservationsCount = $finishedReservations->total();

        $cancelledReservationsAll = $allReservationsQuery->where('cancelled', true);
        $cancelledReservations = $cancelledReservationsAll->paginate(10);
        $cancelledReservationsCount = $cancelledReservations->total();

        return Inertia::render('Structures/Gestion/Reservations/Index', [
            'structure' => fn () => $structure,
            'structureNotifs' => fn () => $structureNotifs,
            'allReservationsCount' => fn () => $allReservationsCount,
            'confirmedReservations' => fn () => $confirmedReservations,
            'confirmedReservationsCount' => fn () => $confirmedReservationsCount,
            'finishedReservations' => fn () => $finishedReservations,
            'finishedReservationsCount' => fn () => $finishedReservationsCount,
            'cancelledReservations' => fn () => $cancelledReservations,
            'cancelledReservationsCount' => fn () => $cancelledReservationsCount,
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if(!$user) {
            return to_route('login')->with('message', 'Connectez vous pour effectuer vos réservations');
        }
        request()->validate([
            'reservations' => ['required'],
            'quantity' => ['nullable'],
            'codePromo' => ['nullable'],
        ]);
        $reservations = $request->reservations;
        if($reservations) {
            foreach($reservations as $reservation) {
                $resa = ProductReservation::withRelations()->find($reservation['id']);

                if ($resa) {
                    $resa->pending = true;
                    $resa->user_id = $user->id;
                    $resa->save();
                }
            }
        }

        if($request->quantity) {
            foreach($request->quantity as $prod => $quantite) {
                $resa = ProductReservation::withRelations()->find($prod);
                if(is_array($quantite)) {
                    foreach($quantite as $creneau => $quantDeCreneau) {

                        $resa->plannings()->updateExistingPivot($creneau, ['quantity' => $quantDeCreneau]);
                    }
                } else {
                    $resa->update(['quantity' => $quantite]);
                }
            }
        }

        return to_route('panier.paiement.index')->with('success', 'Finalisez vos réservations');
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
    public function update(Request $request, Structure $structure, ProductReservation $reservation): RedirectResponse
    {
        $reservation = $reservation->withRelations()->findOrFail($reservation->id);

        if($request->status === "confirmed") {
            $randomCode = strval(rand(0000, 9999));

            $reservation->update([
                'confirmed' => true,
                'datetime_structure_confirmed' => now(),
                'pending' => false,
                'finished' => false,
                'code' => $randomCode,
            ]);

            //envoie email avec le code à 4 chiffres + notification structure + admin
            Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));

            return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Réservation modifiée, un email de confirmation avec le code a été envoyé à l\'utlilisateur.');

        } elseif($request->status === "refused") {
            $reservation->update([
                'confirmed' => false,
                'pending' => false,
                'finished' => false,
                'cancelled' => true,
                'datetime_structure_cancelled' => now(),
                'code' => null,
            ]);
            return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Réservation refusée.');

            // email refusée
        } elseif($request->status === "finished") {
            $code = $reservation->code;

            $request->validate([
                "code" => ['required',
                            'string',
                            'size:4',
                            'regex:/^[0-9]{4}$/',
                            function ($attribute, $value, $fail) use ($code) {
                                if ($value !== $code) {
                                    $fail('Le code de la réservation est érroné.');
                                }
                            },
                ],
            ]);

            if($request->code === $code) {
                $reservation->update([
                            'confirmed' => false,
                            'pending' => false,
                            'finished' => true,
                            'datetime_structure_finished' => now(),
                            'cancelled' => false,
                        ]);
                //email terminée
                return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Code vérifié, la réservation est validée.');

            } else {
                return to_route('structures.gestion.reservations.index', $structure)->with('error', 'Le code de la réservation est érroné.');
            }

        } elseif($request->status === "cancelled") {
            $reservation->update([
                'confirmed' => false,
                'pending' => false,
                'finished' => false,
                'cancelled' => true,
                'datetime_structure_cancelled' => now(),
                'code' => null,
            ]);
            //email annulée
            return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Réservation annulée.');
        }

        // envoie email à l'user et la structure
        return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Réservation modifiée.');
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
