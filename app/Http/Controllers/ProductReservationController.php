<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ReservationAsked;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class ProductReservationController extends Controller
{
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
            ->latest()
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
            ->latest()
            ->get();

        $confirmedReservationsCount = $confirmedReservations->count();

        $finishedReservations = ProductReservation::with([
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
                    ->where('finished', true)
                    ->latest()
                    ->get();

        $finishedReservationsCount = $finishedReservations->count();


        $cancelledReservations = ProductReservation::with([
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
                    'activites' => function ($query) {
                        $query->latest()->limit(3);
                    },
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
    public function store(Request $request)
    {
        request()->validate([
            'produit' => ['required', Rule::exists('structures_produits', 'id')],
            'formule' => ['required', Rule::exists('structures_tarifs', 'id')],
            'planning' => ['nullable', Rule::exists('structure_produits_planning', 'id')],
        ]);
        if(!auth()->user()) {
            return to_route('login')->with('error', 'Vous devez vous authentifier pour effectuer la demande');
        }

        $user = auth()->user();
        $produit = StructureProduit::where('id', $request->produit)->first();
        $tarif = StructureTarif::where('id', $request->formule)->first();
        $planning = StructurePlanning::where('id', $request->planning)->first();

        $structure = $produit->structure;
        $email = $produit->structure->email;

        $activiteId = $produit->activite->id;
        $activite = StructureActivite::where('id', $activiteId)->first();

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

        // dd($produit, $planning, $tarif, $email, $activite, $user);

        return Redirect::back()->with('success', "La demande d'information a été envoyée à la structure");

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
    public function update(Request $request, Structure $structure, ProductReservation $reservation)
    {

        if($request->status === "confirmed") {
            $randomCode = strval(rand(0000, 9999));

            $reservation->update([
                'confirmed' => true,
                'pending' => false,
                'finished' => false,
                'code' => $randomCode,
            ]);

            //envoie email avec le code à 4 chiffres
            return to_route('structures.gestion.reservations.index', $structure)->with('success', 'Réservation modifiée.');

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
