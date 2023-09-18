<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\LienDisciplineCategorie;

class DepartementDisciplineCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Departement $departement, $discipline, $category)
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

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('id', $category)->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();


        $departement = Departement::with([
                    'structures',
                    'cities' => function ($query) {
                        $query->has('produits')->with(['produits', 'produits.adresse']);
                    }])
                                    ->select(['id', 'numero', 'departement', 'prefixe', 'view_count', 'latitude', 'longitude'])
                                    ->where('id', $departement->id)
                                    ->withCount('structures')
                                    ->first();


        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
                        ->where('discipline_id', $discipline->id)
                        ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $categoriesWithoutProduit = LienDisciplineCategorie::whereDoesntHave('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
        ->where('discipline_id', $discipline->id)
        ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->get();

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $citiesAround = $departement->cities()->whereHas('structures')
                            ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                            ->limit(10)
                            ->get();


        $structures = $departement->structures()->with([
            'creator:id,name',
            'users:id,name',
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines' => function ($query) use ($discipline) {
                $query->where('discipline_id', $discipline->id);
            },
            'disciplines.discipline:id,name,slug',
            'categories' => function ($query) use ($category) {
                $query->where('categorie_id', $category->id);
            },
            'activites' => function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)
                    ->where('categorie_id', $category->id);
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
        ->whereHas('activites', function ($query) use ($discipline, $category) {
            $query->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id);
        })
        ->paginate(6);

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Disciplines/Categories/Show', [
            'familles' => $familles,
            'category' => $category,
            'categories' => $categories,
            'categoriesWithoutProduit' => $categoriesWithoutProduit,
            'allStructureTypes' => $allStructureTypes,
            'departement' => $departement,
            'citiesAround' => $citiesAround,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'structures' => $structures,
            'discipline' => $discipline,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
        ]);

    }

}
