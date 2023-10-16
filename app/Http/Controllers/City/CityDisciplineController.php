<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorie;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityDisciplineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, $discipline)
    {

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $discipline = ListDiscipline::with('structureProduits')->where('slug', $discipline)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $city = City::with(['structures', 'produits', 'produits.adresse'])
                            ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                            ->where('slug', $city->slug)
                            ->withCount('produits')
                            ->first();

        $citiesAround = City::withWhereHas('produits')
                            ->select('id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                            ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                            ->where('slug', '!=', $city->slug)
                            ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                            ->orderBy('distance', 'ASC')
                            ->limit(10)
                            ->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city, $cityAroundIds) {
            $query->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
        })
        ->where('discipline_id', $discipline->id)
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
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
        })
                ->select(['id', 'name', 'slug'])
                ->get();

        $citiesAroundProducts = $citiesAround->flatMap(function ($city) use ($discipline) {
            return $city->produits()->with([
                'structure:id,name',
                'adresse',
                'discipline:id,name,slug',
                'activite:id,titre',
                'criteres:id,activite_id,produit_id,critere_id,valeur',
                'criteres.critere:id,nom',
                'tarifs',
                'tarifs.tarifType',
                'tarifs.structureTarifTypeInfos',
                'plannings',
            ])->where('discipline_id', $discipline->id)->get();
        });

        $produitsFromCity = $city->produits()->with([
            'structure:id,name',
            'adresse',
            'discipline:id,name,slug',
            'activite:id,titre',
            'criteres:id,activite_id,produit_id,critere_id,valeur',
            'criteres.critere:id,nom',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])->where('discipline_id', $discipline->id)
        ->get();

        $produits = $produitsFromCity->merge($citiesAroundProducts)->paginate(12);

        $citiesAroundStructures = $citiesAround->flatMap(function ($city) use ($discipline) {
            return $city->structures()->with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,slug,ville,ville_formatee,code_postal',
                'structuretype:id,name,slug',
                'activites' => function ($query) use ($discipline) {
                    $query->where('discipline_id', $discipline->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->whereHas('activites', function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            })
            ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])->get();
        });

        $structuresFromCity = $city->structures()->with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,slug,ville,ville_formatee,code_postal',
            'structuretype:id,name,slug',
            'activites' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'activites.discipline:id,name,slug',
            'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])->whereHas('activites', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })
        ->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])
        ->get();

        $structures = $structuresFromCity->merge($citiesAroundStructures)->paginate(12);

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Show', [
            'familles' => $familles,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
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
