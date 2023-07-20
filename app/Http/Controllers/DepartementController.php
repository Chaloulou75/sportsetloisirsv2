<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();
        $familles = Famille::select(['id', 'name', 'slug'])->get();

        $departements = Departement::with([
                            'structures:id,name,slug,presentation_courte,address,city,zip_code,address_lat,address_lng,departement_id'
                        ])
                        ->whereHas('structures')
                        ->select(['id', 'departement', 'numero'])
                        ->withCount('structures')
                        ->filter(
                            request(['search'])
                        )
                        ->orderByDesc('structures_count')
                        ->paginate(15)
                        ->withQueryString();

        return Inertia::render('Departements/Index', [
            'departements' => $departements,
            'familles' => $familles,
            'structuresCount' => $structuresCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        $familles = Famille::select(['id', 'name', 'slug'])->get();

        $departement = Departement::with(['cities',
                                        'structures' => function ($query) {
                                            $query->latest();
                                        },
                                        'structures.structuretype:id,name,slug'
                                    ])
                                    ->whereHas('structures')
                                    ->select(['id', 'numero', 'departement', 'prefixe', 'view_count'])
                                    ->where('numero', $departement->numero)
                                    ->withCount('structures')
                                    ->first();

        $structures = $departement->structures()->with([
            'famille:id,name',
            'creator:id,name',
            'users:id,name',
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines',
            'disciplines.discipline:id,name,slug',
            'categories:id,categorie_id',
            'activites',
            'activites.discipline',
            'activites.categorie',
            'produits',
            'produits.criteres',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])->withCount('disciplines', 'produits', 'activites')->paginate(6);

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Show', [
            'familles' => $familles,
            'departement'=> $departement,
            'structures' => $structures,
        ]);
    }

}
