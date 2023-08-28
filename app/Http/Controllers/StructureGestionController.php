<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StructureGestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure)
    {

        $structure = Structure::with([
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'creator:id,name,email',
                    'users:id,name',
                    'cities:id,ville,ville_formatee',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    ])
                    ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'abo_news', 'abo_promo', 'logo'])
                    ->where('slug', $structure->slug)
                    ->firstOrFail();


        return Inertia::render('Structures/Gestion/Index', [
            'structure' => $structure,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
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
}
