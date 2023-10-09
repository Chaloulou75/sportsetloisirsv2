<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Departement;
use Inertia\Inertia;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\StructureActivite;
use App\Models\LienDisciplineCategorieCritere;

class DepartementDisciplineStructuretypeActiviteController extends Controller
{
    public function show(Departement $departement, $discipline, $structuretype, $activite, ?string $produit = null)
    {
        $familles = Famille::withWhereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                                        ->select(['id', 'slug',  'code_postal', 'ville', 'ville_formatee'])
                                        ->get();

        $departement = Departement::with(['cities' => function ($query) {
            $query->withWhereHas('produits');
        }])
                ->where('id', $departement->id)
                ->select(['id', 'numero', 'departement', 'prefixe', 'view_count'])
                ->first();


        $discipline = ListDiscipline::with('structureProduits')->where('slug', $discipline)
                                    ->select(['id', 'name', 'slug', 'view_count'])
                                    ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

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
                    'departement' => $departement,
                    'discipline' => $discipline,
                    'disciplinesSimilaires' => $disciplinesSimilaires,
                    'structure' => $structure,
                    'familles' => $familles,
                    'listDisciplines' => $listDisciplines,
                    'allCities' => $allCities,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'activiteSimilaires' => $activiteSimilaires
        ]);

    }
}
