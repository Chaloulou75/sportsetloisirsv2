<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\StructureActivite;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class CityDisciplineCategorieStructureActiviteController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, $discipline, $category, $structure, $activite)
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

        $city = City::with(['structures'])->select(['id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                                    ->where('id', $city->id)
                                    ->withCount('structures')
                                    ->first();


        $discipline = ListDiscipline::where('slug', $discipline)
                                    ->select(['id', 'name', 'slug', 'view_count'])
                                    ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()->select(['famille', 'name', 'slug'])->whereHas('structures')->get();

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('id', $category)->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();

        $categories = LienDisciplineCategorie::where('discipline_id', $discipline->id)->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->get();

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $structure = Structure::with([
                'creator:id,name',
                'users:id,name',
                'adresses'  => function ($query) {
                    $query->latest();
                },
            ])->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
            ->where('slug', $structure)
            ->first();

        $logoUrl = asset($structure->logo);

        $activite = StructureActivite::with([
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits' => function ($query) use ($city) {
                $query->whereHas('adresse', function ($query) use ($city) {
                    $query->where('city_id', $city->id);
                });
            },
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
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();


        $citiesAround = City::with('structures')
                            ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                            ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                            ->whereHas('structures')
                            ->where('id', '!=', $city->id)
                            ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                            ->orderBy('distance', 'ASC')
                            ->limit(10)
                            ->get();


        return Inertia::render('Villes/Disciplines/Categories/Structures/Activites/Show', [
                    'familles' => $familles,
                    'category' => $category,
                    'categories' => $categories,
                    'allStructureTypes' => $allStructureTypes,
                    'city' => $city,
                    'discipline' => $discipline,
                    'structure' => $structure,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'activiteSimilaires' => $activiteSimilaires,
                    'citiesAround' => $citiesAround,
                    'disciplinesSimilaires' => $disciplinesSimilaires,
        ]);
    }
}
