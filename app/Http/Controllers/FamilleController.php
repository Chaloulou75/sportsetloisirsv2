<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
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
        $disciplinesCount = ListDiscipline::count();
        $structuresCount = Structure::count();

        return Inertia::render('Familles/Index', [
            'familles' => $familles,
            'familleCount' => $familleCount,
            'disciplinesCount' => $disciplinesCount,
            'structuresCount' => $structuresCount,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Famille $famille)
    {
        $famille = Famille::with(['disciplines'])
                            ->where('slug', $famille->slug)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->withCount(['disciplines'])
                            ->first();

        $famille->timestamps = false;
        $famille->increment('view_count');

        return Inertia::render('Familles/Show', [
            'famille'=> $famille,
        ]);
    }

    public function loadFamilles()
    {
        $familles = Famille::select(['id', 'name'])->get();
        return FamilleResource::collection($familles);
    }
}
