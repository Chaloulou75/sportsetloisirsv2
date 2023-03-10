<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $clubsCount = Club::count();

        $disciplines = Discipline::select(['id', 'name', 'slug'])
                        // ->withCount('clubs')
                        ->filter(
                            request(['search'])
                        )
                        // ->orderByDesc('clubs_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Discipline/Index', [
            'disciplines' => $disciplines,
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
    public function show(Discipline $discipline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipline $discipline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discipline $discipline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discipline $discipline)
    {
        //
    }
}
