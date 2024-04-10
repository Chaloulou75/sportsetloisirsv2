<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;

class PanierCoordonneesController extends Controller
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

        $user = auth()->user();

        return Inertia::render('Panier/Coordonnees/Index', [
            'user' => fn () => UserResource::make($user) ?? null,
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
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

        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone' => 'required|phone:FR',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'to_offer' => 'boolean',
            'name_receiver' => 'nullable|string|min:3',
            'email_receiver' => 'nullable|email',
            'phone_receiver' => 'nullable|phone:FR',
        ]);

        dd($request->all());
        // création compte client (+1 si client receveur)
        // table client / reservation ? many to many?
        // Notification compte client

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
}
