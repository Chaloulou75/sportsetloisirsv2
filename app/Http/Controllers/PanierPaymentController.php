<?php

namespace App\Http\Controllers;

use Error;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Invoice;
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
use App\Notifications\ReservationPaid;
use App\Http\Resources\FamilleResource;
use Stripe\Exception\ApiErrorException;
use App\Notifications\ReservationPaidToStructure;
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
        $reservations = ProductReservation::withRelations()
                        ->withCount('plannings')
                        ->where('user_id', $user->id)
                        ->where('paid', false)
                        ->get();

        $lineItems = [];
        $totalPrice = 0;
        if($reservations->isNotEmpty()) {
            foreach ($reservations as $reservation) {

                $description = ($reservation->activite->description !== '' && $reservation->activite->description !== null) ? $reservation->activite->description : "Pas de description";

                if ($reservation->plannings_count > 0) {
                    foreach ($reservation->plannings as $planning) {
                        $price = $planning->pivot->quantity * $reservation->tarif_amount;
                        $totalPrice += $price;
                        $lineItems[] = [
                            'price_data' => [
                                'currency' => 'eur',
                                'product_data' => [
                                    'name' => $reservation->activite_title,
                                    'description' => $description,
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
                                'description' => $description,
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
                'invoice_creation' => ['enabled' => true],
                'success_url' => $successUrl,
                'cancel_url' => route('panier.paiement.cancel'),
            ]);

            foreach ($reservations as $reservation) {
                $reservation->stripe_session_id = $sessionStripe->id;
                $reservation->save();
            }

            return Inertia::location($sessionStripe->url);
        } else {
            return to_route(route('panier.index'))->with("message", "Votre panier est vide, ajoutez des produits à votre panier.");
        }

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

            $reservations = ProductReservation::withRelations()->where('stripe_session_id', $sessionStripe->id)
            ->where('paid', false)
            ->get();

            if ($reservations->isNotEmpty()) {
                $user = auth()->user();
                foreach ($reservations as $reservation) {
                    $reservation->update([
                        'user_payeur_id' => $user->id,
                        'paid' => true,
                        'paiement_method' => $sessionStripe->payment_method_types[0],
                        'paiement_datetime' => now(),
                    ]);
                    $user->notify(new ReservationPaid($reservation, $sessionStripe));

                    $reservation->structure->notify(new ReservationPaidToStructure($reservation, $sessionStripe));

                }
            }
            session()->forget('panierProducts');

            return Inertia::render('Panier/Payment/Success', [
                'familles' => fn () => FamilleResource::collection($familles),
                'listDisciplines' => fn () => $listDisciplines,
                'allCities' => fn () => $allCities,
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
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('VITE_STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $sessionStripe = $event->data->object;

                $reservations = ProductReservation::withRelations()
                ->where('stripe_session_id', $sessionStripe->id)
                ->where('paid', false)
                ->get();

                if ($reservations->isNotEmpty()) {
                    $user = auth()->user();
                    // Update all matching reservations at once
                    foreach ($reservations as $reservation) {
                        $reservation->update([
                            'user_payeur_id' => $user->id,
                            'paid' => true,
                            'paiement_method' => $sessionStripe->payment_method_types[0],
                            'paiement_datetime' => now(),
                        ]);
                        $user->notify(new ReservationPaid($reservation, $sessionStripe));

                        $reservation->structure->notify(new ReservationPaidToStructure($reservation, $sessionStripe));

                    }
                }


                // ... handle other event types
                // no break
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        return response('');
    }
}
