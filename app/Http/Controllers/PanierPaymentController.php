<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\ListDiscipline;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use Stripe\Exception\ApiErrorException;

class PanierPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        $sessionId = session()->getId();
        $user = auth()->user();

        if ($user || $sessionId) {
            $query = ProductReservation::withRelations()->withCount('plannings');

            if (isset($user) && $sessionId) {
                $query->where('user_id', $user->id)->orWhere('session_id', $sessionId);
            } elseif ($sessionId) {
                $query->where('session_id', $sessionId);
            } elseif ($user) {
                $query->where('user_id', $user->id);
            }
            $reservations = $query->get();
        }

        $totalPrice = 0;
        foreach($reservations as $reservation) {
            if($reservation->plannings) {
                foreach($reservation->plannings as $planning) {
                    $price = $planning->pivot->quantity * $reservation->tarif_amount;
                    $totalPrice += $price;
                }
            } else {
                $price = $reservation->tarif_amount * $reservation->quantity;
                $totalPrice += $price;
            }
        }


        $stripe = new StripeClient(env('VITE_STRIPE_SECRET'));
        $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $totalPrice * 100,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        $clientSecret = $paymentIntent->client_secret;

        return Inertia::render('Panier/Payment/Index', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'reservations' => fn () => $reservations ?? null,
            'user' => fn () =>  $user ?? null,
            'totalPrice' => fn () => $totalPrice ?? null,
            'clientSecret' => fn () => $clientSecret ?? null,
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



    public function createCheckoutSession(Request $request)
    {
        dd($request->all());

        // $stripeCharge = (new User())->charge(100, $paymentMethod);

        $totalPrice = $request->input('totalPrice');
        try {
            Stripe::setApiKey(env('VITE_STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Réservations de Produits',
                            ],
                            'unit_amount' => $totalPrice * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('panier.paiement.success'),
                'cancel_url' => route('panier.paiement.cancel'),
            ]);

            // Return the session ID
            return response()->json(['sessionId' => $session->id]);
        } catch (ApiErrorException $e) {
            // Handle Stripe API error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {

        dd($request->all());

    }

    public function cancel()
    {
        dd('payment cancelled');
    }
}
