<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Structure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ReservationAsked;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureCatTarif;
use App\Models\StructurePlanning;
use App\Mail\ReservationConfirmed;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ProductReservationController extends Controller
{
    public function index(Structure $structure): Response
    {

        $allReservations = ProductReservation::withRelations()->get();

        $allReservationsCount = $allReservations->count();

        $pendingReservations = ProductReservation::withRelations()
            ->where('pending', true)
            ->latest()
            ->get();

        $pendingReservationsCount = $pendingReservations->count();

        $confirmedReservations = ProductReservation::withRelations()
            ->where('confirmed', true)
            ->latest()
            ->get();

        $confirmedReservationsCount = $confirmedReservations->count();

        $finishedReservations = ProductReservation::withRelations()
                    ->where('finished', true)
                    ->latest()
                    ->get();

        $finishedReservationsCount = $finishedReservations->count();

        $cancelledReservations = ProductReservation::withRelations()
                            ->where('cancelled', true)
                            ->latest()
                            ->get();

        $cancelledReservationsCount = $cancelledReservations->count();


        $totalAmountConfirmed = $confirmedReservations->sum(function ($reservation) {
            return $reservation->tarif->amount;
        });

        $totalAmountPending = $pendingReservations->sum(function ($reservation) {
            return $reservation->tarif->amount;
        });

        $structure = Structure::withRelations()
                    ->with(['activites' => function ($query) {
                        $query->latest()->limit(3);
                    }])
                    ->where('slug', $structure->slug)
                    ->firstOrFail();

        return Inertia::render('Structures/Gestion/Reservations/Index', [
            'structure' => $structure,
            'allReservations' => $allReservations,
            'allReservationsCount' => $allReservationsCount,
            'confirmedReservations' => $confirmedReservations,
            'confirmedReservationsCount' => $confirmedReservationsCount,
            'finishedReservations' => $finishedReservations,
            'finishedReservationsCount' => $finishedReservationsCount,
            'cancelledReservations' => $cancelledReservations,
            'cancelledReservationsCount' => $cancelledReservationsCount,
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'produitId' => ['required', Rule::exists(StructureProduit::class, 'id')],
            'catTarifId' => ['nullable', Rule::exists(StructureCatTarif::class, 'id')],
            'attributs' => ['nullable', 'array'],
            'sousattributs' => ['nullable', 'array'],
            'plannings' => ['nullable', 'array'],
        ]);
        dd($request->all());
        $produit = StructureProduit::find($request->produitId);
        $tarif = StructureCatTarif::find($request->formule);
        $planning = StructurePlanning::find($request->planning);

        $structure = $produit->structure;
        $email = $produit->structure->email;

        $activiteId = $produit->activite->id;
        $activite = StructureActivite::find($activiteId);

        if(!auth()->user()) {
            $sessionPanierProducts = session()->get('panierProducts', []);
            $sessionPanierProducts[] = [
                'ip_address' => $request->ip(),
                'produit_id' => $produit->id,
                'tarif_id' => $tarif->id ?? null
            ];
            session()->put('panierProducts', $sessionPanierProducts);

            return to_route('structures.activites.show', ['activite' => $activite])->with('success', 'Produit ajouté à votre panier');
        }

        $user = auth()->user();

        if($user) {
            $sessionPanierProducts = session()->get('panierProducts', []);
            $sessionPanierProducts[] = [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'produit_id' => $produit->id,
                'tarif_id' => $tarif->id ?? null
            ];
            session()->put('panierProducts', $sessionPanierProducts);

            if($planning) {
                $newReservation = ProductReservation::create([
                    'user_id' => $user->id,
                    'produit_id' => $produit->id,
                    'tarif_id' => $tarif->id,
                    'planning_id' => $planning->id,
                    'pending' => true,
                    'confirmed' => false,
                    'finished' => false,
                    'cancelled' => false,
                ]);

                Mail::to($email)->send(new ReservationAsked($structure, $activite, $produit, $planning, $tarif, $user));
            }
        }

        return to_route('structures.activites.show', ['activite' => $activite])->with('success', "Produit ajouté à votre panier");
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
        $user = User::find($reservation->user_id);
        $userEmail = $user->email;
        $produit = StructureProduit::find($reservation->produit_id);
        $tarif = StructureCatTarif::find($reservation->tarif_id);
        $planning = StructurePlanning::find($reservation->planning_id);
        $activiteId = $produit->activite->id;
        $activite = StructureActivite::find($activiteId);


        if($request->status === "confirmed") {
            $randomCode = strval(rand(0000, 9999));

            $reservation->update([
                'confirmed' => true,
                'pending' => false,
                'finished' => false,
                'code' => $randomCode,
            ]);

            //envoie email avec le code à 4 chiffres
            Mail::to($userEmail)->send(new ReservationConfirmed($activite, $produit, $planning, $tarif, $reservation, $user));

            return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Réservation modifiée, un email de confirmation avec le code a été envoyé à l\'utlilisateur.');

        } elseif($request->status === "refused") {
            $reservation->update([
                'confirmed' => false,
                'pending' => false,
                'finished' => false,
                'cancelled' => true,
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
}
