<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorieCritere;

class CityActiviteController extends Controller
{
    public function show(City $city, $activite, ?string $produit = null)
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $city = City::with([
                        'structures',
                        'produits',
                        'produits.adresse'
                    ])
                    ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                    ->where('slug', $city->slug)
                    ->withCount('structures')
                    ->first();

        $citiesAround = City::with('structures', 'produits', 'produits.adresse')
                            ->select('id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                            ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                            ->where('id', '!=', $city->id)
                            ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                            ->orderBy('distance', 'ASC')
                            ->limit(10)
                            ->get();

        dd($citiesAround);

        $activite = StructureActivite::with([
            'structure',
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
            'produits.plannings',
        ])->where('id', $activite)->first();

        $structure = $activite->structure()->with([
                    'creator:id,name',
                    'users:id,name',
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    ])
                    ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
                    ->first();

        $logoUrl = asset($structure->logo);

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
                ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
                ->get();

        $activiteSimilaires = StructureActivite::with([
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.plannings'
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
                    'structure' => $structure,
                    'familles' => $familles,
                    'listDisciplines' => $listDisciplines,
                    'allCities' => $allCities,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'city' => $city,
                    'citiesAround' => $citiesAround,
                    'activiteSimilaires' => $activiteSimilaires
        ]);
    }
}
