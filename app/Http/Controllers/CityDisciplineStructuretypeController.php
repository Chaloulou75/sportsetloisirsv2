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

        $familles = Famille::withWhereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();


        $discipline = ListDiscipline::where('slug', $discipline)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();
        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $city = City::with(['structures', 'produits', 'produits.adresse'])->select(['id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
                            ->where('id', $city->id)
                            ->withCount('structures')
                            ->first();

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city) {
            $query->where('city_id', $city->id);
        })
                        ->where('discipline_id', $discipline->id)
                        ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $categoriesWithoutProduit = LienDisciplineCategorie::whereDoesntHave('structures_produits.adresse', function (Builder $query) use ($city) {
            $query->where('city_id', $city->id);
        })
        ->where('discipline_id', $discipline->id)
        ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->get();


        $citiesAround = City::with('structures')
                    ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                    ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                    ->where('id', '!=', $city->id)
                    ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                    ->orderBy('distance', 'ASC')
                    ->limit(10)
                    ->get();


        $structures = $city->structures()->whereHas('disciplines', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })->where('structuretype_id', $structuretypeElected->id)->with([
            'famille:id,name',
            'creator:id,name',
            'users:id,name',
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines.discipline:id,name,slug',
            'categories',
            'activites' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'activites.discipline',
            'activites.categorie',
            'produits',
            'produits.criteres',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])->withCount('disciplines', 'produits', 'activites')
        ->whereHas('activites')
        ->paginate(6);

        $city->timestamp = false;
        $city->increment('view_count');

        return Inertia::render('Villes/Disciplines/Structuretypes/Show', [
            'familles' => $familles,
            'structuretypeElected' => $structuretypeElected,
            'allStructureTypes' => $allStructureTypes,
            'categories' => $categories,
            'categoriesWithoutProduit' => $categoriesWithoutProduit,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'structures' => $structures,
            'discipline' => $discipline,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
        ]);

    }
}
