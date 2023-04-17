<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\FamilleResource;
use Illuminate\Database\Eloquent\Builder;

class FamilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $familles = Famille::select(['id', 'name', 'slug'])->get();

        $familleCount = Famille::count();
        $disciplinesCount = Discipline::count();
        $structuresCount = Structure::count();

        return Inertia::render('Familles/Index', [
            'familles' => $familles,
            'familleCount' => $familleCount,
            'disciplinesCount' => $disciplinesCount,
            'structuresCount' => $structuresCount,
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
    public function show(Famille $famille)
    {
        $famille = Famille::with(['disciplines' => function ($query) {
            $query->withCount('activites')->orderByDesc('activites_count');
        }])
                            ->where('slug', $famille->slug)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->withCount(['disciplines'])
                            ->first();

        $totalActivites = $famille->disciplines->sum('activites_count');

        $famille->timestamps = false;
        $famille->increment('view_count');

        return Inertia::render('Familles/Show', [
            'famille'=> $famille,
            'totalActivites' => $totalActivites,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Famille $famille)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Famille $famille)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Famille $famille)
    {
        //
    }

    public function loadFamilles()
    {
        $familles = Famille::select(['id', 'name'])->get();
        return FamilleResource::collection($familles);
    }
}
