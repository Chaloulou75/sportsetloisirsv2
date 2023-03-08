<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activite;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $clubsCount = Club::count();

        $activites = Activite::select(['id', 'name', 'slug'])
                        // ->withCount('clubs')
                        ->filter(
                            request(['search'])
                        )
                        // ->orderByDesc('clubs_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Activite/Index', [
            'activites' => $activites,
            // 'clubsCount' => $clubsCount,
            'filters' => request()->all(['search']),
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
    public function show(Activite $activite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activite $activite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activite $activite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activite $activite)
    {
        //
    }
}
