<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\StructureTypeInfo;
use App\Models\LienDisciplineCategorie;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityDisciplineStructuretypeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, $discipline, $structuretype)
    {
        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();

        $discipline = ListDiscipline::with('structureProduits')->where('slug', $discipline)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();



        $city = City::with(['produits', 'produits.adresse'])->select(['id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                            ->where('id', $city->id)
                            ->withCount('structures')
                            ->first();

        $citiesAround = City::withWhereHas('produits')
                    ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                    ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                    ->where('id', '!=', $city->id)
                    ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                    ->orderBy('distance', 'ASC')
                    ->limit(10)
                    ->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city, $cityAroundIds) {
            $query->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
        })
                        ->where('discipline_id', $discipline->id)
                        ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);


        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $discipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
            });
        })->select(['id', 'name', 'slug'])
                ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) use ($discipline, $structuretypeElected) {
            return $city->produits()->with([
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
            ])->where('discipline_id', $discipline->id)
            ->whereHas('structure', function ($query) use ($structuretypeElected) {
                $query->where('structuretype_id', $structuretypeElected->id);
            })->get();
        });

        $produitsFromCity = $city->produits()->with([
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
        ])->where('discipline_id', $discipline->id)
        ->whereHas('structure', function ($query) use ($structuretypeElected) {
            $query->where('structuretype_id', $structuretypeElected->id);
        })
        ->get();

        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) use ($discipline, $structuretypeElected) {
            return $city->structures()->with([
                'creator:id,name',
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,ville,ville_formatee,code_postal',
                'departement:id,departement,numero',
                'structuretype:id,name,slug',
                'disciplines' => function ($query) use ($discipline) {
                    $query->where('id', $discipline->id);
                },
                'disciplines.discipline:id,name,slug',
                'categories',
                'activites' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
                'produits' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'produits.criteres:id,activite_id,produit_id,critere_id,valeur',
                'produits.criteres.critere:id,nom',
            ])->whereHas('activites', function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            })
            ->where('structuretype_id', $structuretypeElected->id)
            ->withCount('disciplines', 'produits', 'activites')
            ->select(['id', 'name', 'slug', 'presentation_courte', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
            ->get();
        });

        $structuresFromCity = $city->structures()->with([
            'creator:id,name',
            'adresses' => function ($query) {
                $query->latest();
            },
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines' => function ($query) use ($discipline) {
                $query->where('id', $discipline->id);
            },
            'disciplines.discipline:id,name,slug',
            'categories',
            'activites' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'activites.discipline:id,name,slug',
            'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            'produits',
            'produits.criteres:id,activite_id,produit_id,critere_id,valeur',
            'produits.criteres.critere:id,nom',
        ])
        ->where('structuretype_id', $structuretypeElected->id)
        ->whereHas('activites', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })
        ->withCount('disciplines', 'produits', 'activites')
        ->select(['id', 'name', 'slug', 'presentation_courte', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
        ->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Structuretypes/Show', [
            'familles' => $familles,
            'structuretypeElected' => $structuretypeElected,
            'allStructureTypes' => $allStructureTypes,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'produits' => $produits,
            'structures' => $structures,
            'discipline' => $discipline,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
        ]);

    }
}
