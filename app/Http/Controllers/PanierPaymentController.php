<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\ListDiscipline;
use App\Models\ProductReservation;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use Stripe\Exception\ApiErrorException;
use App\Http\Resources\ListDisciplineResource;

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

        if (!$customer->stripe_id) {
            $stripeCustomer = $stripe->customers->create([
                'name' => $customer->prenom .' '. $customer->nom,
                'email' => $user->email,
                'address' => [
                        'line1' => $customer->adresse,
                        'postal_code' => $customer->zip_code,
                        'city' => $customer->city,
                        'country' => $customer->country,
                    ],
                'phone' => $customer->phone,
            ]);

            $customer->stripe_id = $stripeCustomer->id;
            $customer->save();
        }

        $intent = $stripe->paymentIntents->create([
            'amount' => $totalPrice * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'customer' => $customer->stripe_id,
        ]);

        foreach ($reservations as $reservation) {
            $reservation->customer_id = $customer->id;
            $reservation->stripe_session_id = $intent->id;
            $reservation->save();
        }

        $returnUrl = route('panier.paiement.success');

        return Inertia::render('Panier/Payment/Index', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'reservations' => fn () => $reservations ?? null,
            'user' => fn () =>  $user ?? null,
            'totalPrice' => fn () => $totalPrice ?? null,
            'clientSecret' => fn () => $intent->client_secret,
            'returnUrl' => fn () => $returnUrl,
        ]);
    }

    public function success(Request $request): Response
    {
        session()->forget('panierProducts');

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        return Inertia::render('Panier/Payment/Success', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
        ]);
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
            'allCities' => fn () => CityResource::collection($allCities),
        ]);
    }
}
