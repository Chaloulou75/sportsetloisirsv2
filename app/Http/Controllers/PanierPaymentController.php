<?php

namespace App\Http\Controllers;

use Error;
use Stripe\Stripe;
use App\Models\City;
use Inertia\Inertia;
use Stripe\Customer;
use Inertia\Response;
use App\Models\Famille;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\ListDiscipline;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PanierPaymentController extends Controller
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

        $user = auth()->user();

        $reservations = ProductReservation::withRelations()->withCount('plannings')->where('user_id', $user->id)->where('paid', false)->get();

        $totalPrice = 0;
        foreach($reservations as $reservation) {
            if($reservation->plannings_count > 0) {
                foreach($reservation->plannings as $planning) {
                    $price = $planning->pivot->quantity * $reservation->tarif_amount;
                    $totalPrice += $price;
                }
            } else {
                $price = $reservation->tarif_amount * $reservation->quantity;
                $totalPrice += $price;
            }

        }
        return Inertia::render('Panier/Payment/Index', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
            'reservations' => fn () => $reservations ?? null,
            'user' => fn () =>  $user ?? null,
            'totalPrice' => fn () => $totalPrice ?? null,
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
        Stripe::setApiKey(env('VITE_STRIPE_SECRET'));

        $user = auth()->user();


        $reservations = ProductReservation::withRelations()->withCount('plannings')->where('user_id', $user->id)->where('paid', false)->get();

        $lineItems = [];
        $totalPrice = 0;

        foreach ($reservations as $reservation) {
            // dd($reservation);
            if ($reservation->plannings_count > 0) {
                foreach ($reservation->plannings as $planning) {
                    $price = $planning->pivot->quantity * $reservation->tarif_amount;
                    $totalPrice += $price;
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $reservation->activite_title,
                                'description' => $reservation->activite->description,
                            ],
                            'unit_amount' => $reservation->tarif_amount * 100,
                        ],
                        'quantity' => $planning->pivot->quantity,
                    ];
                }
            } else {
                $price = $reservation->tarif_amount * $reservation->quantity;
                $totalPrice += $price;
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $reservation->activite_title,

                        ],
                        'unit_amount' => $reservation->tarif_amount * 100,
                    ],
                    'quantity' => $reservation->quantity,
                ];
            }
        }

        $successUrl = route('panier.paiement.success');
        $successUrl .= '?session_id={CHECKOUT_SESSION_ID}';
        $sessionStripe = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'billing_address_collection' => 'required',
            'phone_number_collection' => ['enabled' => true],
            'success_url' => $successUrl,
            'cancel_url' => route('panier.paiement.cancel'),
        ]);

        foreach ($reservations as $reservation) {
            $reservation->stripe_session_id = $sessionStripe->id;
            $reservation->save();
        }

        return Inertia::location($sessionStripe->url);
    }

    public function success(Request $request)
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

        $stripe = Stripe::setApiKey(env('VITE_STRIPE_SECRET'));

        $sessionId = $request->get('session_id');
        try {
            $sessionStripe = Session::retrieve($sessionId);

            if (!$sessionStripe) {
                throw new NotFoundHttpException();
            }

            $reservations = ProductReservation::withRelations()->where('stripe_session_id', $sessionStripe->id)->get();

            foreach ($reservations as $reservation) {
                $reservation->user_payeur_id = auth()->user()->id;
                $reservation->paid = true;
                $reservation->paiement_method = $sessionStripe->payment_method_types[0];
                $reservation->paiement_datetime = now();
                $reservation->save();
            }

            // paiement en database pour chaque resas

            // notifier la structure des résas + paiement
            // mail à l'auteur de la résa
            // notifier l'admin du site

            // supprimer le panier en session
            $panier = $request->session()->get('panierProducts');

            $customer = $sessionStripe->customer_details;
            return Inertia::render('Panier/Payment/Success', [
                'familles' => fn () => FamilleResource::collection($familles),
                'listDisciplines' => fn () => $listDisciplines,
                'allCities' => fn () => $allCities,
                'customer' => $customer ?? null,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new NotFoundHttpException();
        }
    }

    public function cancel(Request $request): Response
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

        return Inertia::render('Panier/Payment/Cancel', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
        ]);

    }

    public function webhook()
    {
        return 'ok';

        // $stripe = new \Stripe\StripeClient(env('VITE_STRIPE_SECRET'));

    }
}
