<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

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
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Departements/Index', [
            'departements' => $departements,
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'structuresCount' => $structuresCount,
            'filters' => request()->all(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $departement = Departement::with(['cities' => function ($query) {
            $query->whereHas('produits');
        }])
        ->where('slug', $departement->slug)
        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
        ->first();

        $departement->load([
            'cities.produits.structure:id,name',
            'cities.produits.adresse',
            'cities.produits.discipline:id,name,slug',
            'cities.produits.activite:id,titre',
            'cities.produits.criteres:id,activite_id,produit_id,critere_id,valeur',
            'cities.produits.criteres.critere:id,nom',
            'cities.produits.tarifs',
            'cities.produits.tarifs.tarifType',
            'cities.produits.tarifs.structureTarifTypeInfos',
            'cities.produits.plannings',
        ]);

        $collectionProduits = $departement->cities->flatMap(function ($city) {
            return $city->produits;
        });

        $flattenedDisciplines = $collectionProduits->pluck('discipline')->unique();

        $produits = $collectionProduits->paginate(12);

        $structuresFlat = $departement->cities->flatMap(function ($city) {
            return $city->structures;
        });

        $structures = $structuresFlat->each(function ($structure) {
            $structure->load([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,code_postal',
                'structuretype:id,name,slug',
                'activites',
                'activites.discipline:id,name,slug',
                'activites.categorie:id,slug,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])->get();
        })->paginate(12);

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Show', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'flattenedDisciplines' => $flattenedDisciplines,
            'allCities' => $allCities,
            'departement' => $departement,
            'produits' => $produits,
            'structures' => $structures,
        ]);
    }

}