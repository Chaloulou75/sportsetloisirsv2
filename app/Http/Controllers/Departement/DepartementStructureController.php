<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DepartementStructureController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Departement $departement, $structure)
    {

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();


        $structure = Structure::with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    'disciplines',
                    'disciplines.discipline:id,name,slug',
                    'categories',
                    'activites',
                    'activites.discipline:id,name',
                    'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client,nom_categorie_pro',
                    'activites.produits',
                    'activites.produits.adresse',
                    'activites.produits.criteres',
                    'activites.produits.criteres.critere',
                    'activites.produits.tarifs',
                    'activites.produits.tarifs.tarifType',
                    'activites.produits.tarifs.structureTarifTypeInfos',
                    'activites.produits.plannings',
                    ])
                    ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
                    ->where('slug', $structure)
                    ->first();

        $logoUrl = asset($structure->logo);


        $departement = Departement::with(['cities' => function ($query) {
            $query->whereHas('produits');
        }])
                        ->where('slug', $departement->slug)
                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                        ->first();


        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
        ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
        ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => $structure,
            'familles' => $familles,
            'allCities' => $allCities,
            'listDisciplines' => $listDisciplines,
            'criteres' => $criteres,
            'logoUrl' => $logoUrl,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'departement' => $departement,
        ]);
    }

}
