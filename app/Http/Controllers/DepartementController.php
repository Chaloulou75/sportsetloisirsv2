<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structuresCount = Structure::count();

        $familles = Famille::withWhereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();


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
        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                                        ->get();

        $departement = Departement::with(['cities' => function ($query) {
            $query->withWhereHas('produits')->select('id', 'nom_departement', 'departement');
        }])
        ->where('id', $departement->id)
        ->select(['id', 'numero', 'departement', 'prefixe', 'view_count'])
        ->first();

        $produitsFlat = $departement->cities
            ->flatMap(function ($city) {
                return $city->produits;
            });

        $produits = $produitsFlat->each(function ($produit) {
            $produit->load([
                'structure:id,name,slug,structuretype_id,address,address_lat,address_lng,zip_code,city_id,city,departement_id,website,view_count',
                'adresse',
                'discipline:id,name,slug,view_count',
                'categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
                'activite:id,discipline_id,categorie_id,structure_id,titre,description,image,actif',
                'activite.discipline:id,name,slug',
                'activite.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
                'criteres:id,activite_id,produit_id,critere_id,valeur',
                'criteres.critere:id,nom',
                'tarifs',
                'tarifs.tarifType',
                'tarifs.structureTarifTypeInfos',
                'plannings',
            ]);
        })->paginate(12);

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Show', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'departement' => $departement,
            'produits' => $produits,
        ]);
    }

}
