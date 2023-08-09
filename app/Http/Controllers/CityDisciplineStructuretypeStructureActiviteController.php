<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureActivite;
use App\Models\LienDisciplineCategorieCritere;

class CityDisciplineStructuretypeStructureActiviteController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, $discipline, $structuretype, $structure, $activite)
    {
        $familles = Famille::with([
                    'disciplines' => function ($query) {
                        $query->whereHas('structures');
                    }
                ])
                ->whereHas('disciplines', function ($query) {
                    $query->whereHas('structures');
                })
                ->select(['id', 'name', 'slug'])
                ->get();

        $discipline = ListDiscipline::where('slug', $discipline)
                                    ->select(['id', 'name', 'slug', 'view_count'])
                                    ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()->select(['famille', 'name', 'slug'])->whereHas('structures')->get();

        $structure = Structure::with([
            'famille:id,name',
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
            'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client',
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
            ->where('slug', $structure->slug)
            ->first();

        $logoUrl = asset($structure->logo);

        $activite = StructureActivite::with([
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
            ])->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Villes/Disciplines/Structuretypes/Structures/Activites/Show', [
                    'structure' => $structure,
                    'familles' => $familles,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'activiteSimilaires' => $activiteSimilaires
        ]);
    }
}
