<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Customer;
use App\Models\ListDiscipline;
use App\Models\ProductReservation;
use App\Http\Resources\CityResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\ListDisciplineResource;

class CustomerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
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

        if($reservations->isEmpty()) {
            return to_route('panier.index')->with('error', 'Votre panier est vide pour l\'instant');
        }

        return Inertia::render('Panier/Customers/Create', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'customer' => fn () => CustomerResource::make($customer) ?? null,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();
        $customer = Customer::updateOrCreate(['user_id' => $user->id], $validated);

        return to_route('panier.paiement.index')->with('success', 'Finalisez vos réservations');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer = $customer->with([
            'user',
            'reservations' => function ($query) {
                $query->withRelations()->isPaid();
            }
        ])->findOrFail($customer->id);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        return Inertia::render('Customers/Show', [
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'customer' => fn () => CustomerResource::make($customer),
        ]);
    }
}
