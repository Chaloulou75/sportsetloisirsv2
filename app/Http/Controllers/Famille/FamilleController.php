<?php

namespace App\Http\Controllers\Famille;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use Illuminate\Database\Eloquent\Builder;

class FamilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
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

        $familleCount = Famille::count();
        $disciplinesCount = Cache::remember('disciplinesCount', 600, function () {
            return ListDiscipline::count();
        });
        $structuresCount = Cache::remember('structuresCount', 600, function () {
            return Structure::count();
        });

        return Inertia::render('Familles/Index', [
            'familles' => fn () => $familles,
            'allCities' => fn () => $allCities,
            'listDisciplines' => fn () => $listDisciplines,
            'familleCount' => fn () => $familleCount,
            'disciplinesCount' => fn () => $disciplinesCount,
            'structuresCount' => fn () => $structuresCount,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Famille $famille): Response
    {

        $familles = Famille::withWhereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();


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
            'familles' => fn () => $familles,
            'allCities' => fn () => $allCities,
            'listDisciplines' => fn () => $listDisciplines,
            'famille' => fn () => $famille,
        ]);
    }

    public function loadFamilles()
    {
        $familles = Famille::select(['id', 'name'])->get();
        return FamilleResource::collection($familles);
    }
}
