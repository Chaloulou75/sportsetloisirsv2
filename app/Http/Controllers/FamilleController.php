<?php

namespace App\Http\Controllers;

use App\Models\City;
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

        $familles = Famille::withWhereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();


        $familleCount = Famille::count();
        $disciplinesCount = ListDiscipline::count();
        $structuresCount = Structure::count();

        return Inertia::render('Familles/Index', [
            'familles' => $familles,
            'allCities' => $allCities,
            'listDisciplines' => $listDisciplines,
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
        $familles = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })->select(['id', 'name', 'slug'])->get();

        $famille = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })
        ->where('slug', $famille->slug)
        ->select(['id', 'name', 'slug', 'view_count'])
        ->withCount(['disciplines'])
        ->first();

        $famille->timestamps = false;
        $famille->increment('view_count');

        return Inertia::render('Familles/Show', [
            'familles' => $familles,
            'famille' => $famille,
        ]);
    }

    public function loadFamilles()
    {
        $familles = Famille::select(['id', 'name'])->get();
        return FamilleResource::collection($familles);
    }
}
