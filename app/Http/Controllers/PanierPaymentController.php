<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Invoice;
use App\Models\City;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Stripe\SetupIntent;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\ListDiscipline;
use App\Models\ProductReservation;
use Laravel\Cashier\Exceptions\PaymentFailure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Notifications\ReservationPaid;
use App\Http\Resources\FamilleResource;
use Stripe\Exception\ApiErrorException;
use App\Notifications\ReservationPaidToAdmin;
use App\Http\Resources\ListDisciplineResource;
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
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $user = auth()->user();
        $customer = $user->customer;

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
        $stripe = new StripeClient(config('services.stripe.secret'));

        $intent = $stripe->paymentIntents->create([
            'amount' => $totalPrice * 100,
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
        ]);
        $returnUrl = route('panier.paiement.success');

        return Inertia::render('Panier/Payment/Index', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => $allCities,
            'reservations' => fn () => $reservations ?? null,
            'user' => fn () =>  $user ?? null,
            'totalPrice' => fn () => $totalPrice ?? null,
            'clientSecret' => fn () => $intent->client_secret,
            'returnUrl' => fn () => $returnUrl,
        ]);
    }

    public function createCheckoutSession(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $user = auth()->user();
            $reservations = ProductReservation::withRelations()
                            ->withCount('plannings')
                            ->where('user_id', $user->id)
                            ->where('paid', false)
                            ->get();

            if ($reservations->isEmpty()) {
                return redirect()->route('panier.index')
                    ->with('message', 'Votre panier est vide, ajoutez des produits à votre panier.');
            }

            $lineItems = [];
            $totalPrice = 0;

            foreach ($reservations as $reservation) {

                $description = ($reservation->activite->description !== '' && $reservation->activite->description !== null) ? $reservation->activite->description : "Pas de description";

                if ($reservation->plannings_count > 0) {
                    foreach ($reservation->plannings as $planning) {

                        $start = Carbon::parse($planning->start)->isoFormat('LLLL');
                        $end = Carbon::parse($planning->end)->isoFormat('LLLL');

                        $price = $planning->pivot->quantity * $reservation->tarif_amount;
                        $totalPrice += $price;
                        $lineItems[] = [
                            'price_data' => [
                                'currency' => 'eur',
                                'product_data' => [
                                    'name' => $reservation->activite_title,
                                    'description' => 'Du ' . $start . ' au ' . $end . ".\n" . $description,
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

            $successUrl = route('panier.paiement.success') . '?session_id={CHECKOUT_SESSION_ID}';

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

        } catch (ApiErrorException $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement de votre paiement. Veuillez réessayer plus tard.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur inattendue est survenue. Veuillez réessayer plus tard.');
        }

    }

    public function purchase(Request $request)
    {
        $user = $request->user();
        // $paymentMethodId = $request->input('payment_method_id');
        // $totalPrice = $request->input('totalPrice');

        dd('yppppppppppppppyyy', $request->payment_intent, $request->payment_intent_client_secret);
        try {
            // Assuming you have the logic to charge the user
            // $user->charge($totalPrice * 100, $paymentMethodId);

            // Update reservations or other necessary logic

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

    }

    public function success(Request $request)
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });
        session()->forget('panierProducts');
        return Inertia::render('Panier/Payment/Success', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => $allCities,
        ]);

        // VERSION CHECKOUT
        // try {
        //     Stripe::setApiKey(config('services.stripe.secret'));

        //     $sessionId = $request->get('session_id');

        //     $sessionStripe = Session::retrieve($sessionId);

        //     if (!$sessionStripe) {
        //         throw new NotFoundHttpException();
        //     }

        //     $reservations = ProductReservation::withRelations()->where('stripe_session_id', $sessionStripe->id)
        //     ->where('paid', false)
        //     ->get();

        //     if ($reservations->isNotEmpty()) {
        //         $user = auth()->user();

        //         foreach ($reservations as $reservation) {

        //             $payment_method = !empty($sessionStripe->payment_method_types) ? $sessionStripe->payment_method_types[0] : null;

        //             $reservation->update([
        //                 'user_payeur_id' => $user->id,
        //                 'paid' => true,
        //                 'paiement_method' => $payment_method,
        //                 'paiement_datetime' => now(),
        //             ]);
        //             $user->notify(new ReservationPaid($reservation, $sessionStripe));

        //             $reservation->structure->notify(new ReservationPaidToStructure($reservation, $sessionStripe));

        //             $admins = User::whereHas('roles', function ($query) {
        //                 $query->where('name', 'admin');
        //             })->get();
        //             if($admins) {
        //                 foreach ($admins as $admin) {
        //                     $admin->notify(new ReservationPaidToAdmin($reservation, $sessionStripe));
        //                 }
        //             }
        //         }
        //     }
        //     session()->forget('panierProducts');

        //     return Inertia::render('Panier/Payment/Success', [
        //         'familles' => fn () => FamilleResource::collection($familles),
        //         'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
        //         'allCities' => fn () => $allCities,
        //     ]);
        // } catch (\Stripe\Exception\ApiErrorException $e) {
        //     throw new NotFoundHttpException();
        // }
    }

    public function cancel(Request $request): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        return Inertia::render('Panier/Payment/Cancel', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => $allCities,
        ]);

    }

    public function webhook()
    {
        $endpoint_secret = config('services.stripe.webhook');

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
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
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

                        $payment_method = !empty($sessionStripe->payment_method_types) ? $sessionStripe->payment_method_types[0] : null;

                        $reservation->update([
                            'user_payeur_id' => $user->id,
                            'paid' => true,
                            'paiement_method' => $payment_method,
                            'paiement_datetime' => now(),
                        ]);
                        $user->notify(new ReservationPaid($reservation, $sessionStripe));

                        $reservation->structure->notify(new ReservationPaidToStructure($reservation, $sessionStripe));

                        $admins = User::whereHas('roles', function ($query) {
                            $query->where('name', 'admin');
                        })->get();
                        if($admins) {
                            foreach ($admins as $admin) {
                                $admin->notify(new ReservationPaidToAdmin($reservation, $sessionStripe));
                            }
                        }
                    }
                }

                // handle other event types
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $reservations = ProductReservation::withRelations()
                                ->where('stripe_session_id', $paymentIntent->id)
                                ->where('paid', false)
                                ->get();

                if ($reservations->isNotEmpty()) {
                    $user = auth()->user();
                    // Update all matching reservations at once
                    foreach ($reservations as $reservation) {

                        $payment_method = !empty($paymentIntent->payment_method_types) ? $paymentIntent->payment_method_types[0] : null;

                        $reservation->update([
                            'user_payeur_id' => $user->id,
                            'paid' => true,
                            'paiement_method' => $payment_method,
                            'paiement_datetime' => now(),
                        ]);
                        $user->notify(new ReservationPaid($reservation, $paymentIntent));

                        $reservation->structure->notify(new ReservationPaidToStructure($reservation, $paymentIntent));

                        $admins = User::whereHas('roles', function ($query) {
                            $query->where('name', 'admin');
                        })->get();
                        if($admins) {
                            foreach ($admins as $admin) {
                                $admin->notify(new ReservationPaidToAdmin($reservation, $paymentIntent));
                            }
                        }
                    }
                }
                // no break
            default:
                return response('Received unknown event type', 400);
        }
        return response('ok', 200);
    }
}
