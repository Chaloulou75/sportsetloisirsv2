<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DepartementDisciplineCategorieController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Departement $departement, $discipline, $category)
    {

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $discipline = ListDiscipline::where('slug', $discipline)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('slug', $category)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();

        $criteres = LienDisciplineCategorieCritere::with('valeurs')->where('discipline_id', $discipline->id)->where('categorie_id', $category->id)->get();

        $departement = Departement::with(['cities' => function ($query) {
            $query->whereHas('produits');
        }])
                        ->where('slug', $departement->slug)
                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                        ->first();

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
                        ->where('discipline_id', $discipline->id)
                        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline, $departement) {
            $query->whereHas('produits', function ($subquery) use ($discipline, $departement) {
                $subquery->where('discipline_id', $discipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($departement) {
                            $addressQuery->whereIn('city_id', $departement->cities->pluck('id'));
                        });
            });
        })
                        ->select(['id', 'name', 'slug'])
                        ->get();

        $departement->load([
            'cities.produits.structure:id,name',
            'cities.produits.adresse',
            'cities.produits.activite:id,titre',
            'cities.produits.criteres:id,activite_id,produit_id,critere_id,valeur',
            'cities.produits.criteres.critere:id,nom',
            'cities.produits.tarifs',
            'cities.produits.tarifs.tarifType',
            'cities.produits.tarifs.structureTarifTypeInfos',
            'cities.produits.plannings',
        ]);

        $produits = $departement->cities->flatMap(function ($city) use ($discipline, $category) {
            return $city->produits->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
        })->paginate(12);

        $structuresFlat = $departement->cities->flatMap(function ($city) use ($discipline, $category) {
            return $city->structures->filter(function ($structure) use ($discipline, $category) {
                return $structure->produits->where('discipline_id', $discipline->id)->where('categorie_id', $category->id)->isNotEmpty();
            });
        });

        $structures = $structuresFlat->each(function ($structure) use ($discipline, $category) {
            $structure->load([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,ville,ville_formatee,code_postal',
                'structuretype:id,name,slug',
                'disciplines' => function ($query) use ($discipline) {
                    $query->where('id', $discipline->id);
                },
                'disciplines.discipline:id,name,slug',
                'categories' => function ($query) use ($category) {
                    $query->where('id', $category->id);
                },
                'activites' => function ($query) use ($discipline, $category) {
                    $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
                },
                'activites.discipline:id,name,slug',
                'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
            ])->whereHas('activites', function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
            })->select(['id', 'name', 'slug', 'structuretype_id', 'address', 'zip_code', 'city', 'address_lat', 'address_lng'])->get();
        })->paginate(12);

        $citiesAround = $departement->cities()->whereHas('produits')
                            ->select('id', 'slug', 'ville')
                            ->limit(10)
                            ->get();

        $departement->timestamp = false;
        $departement->increment('view_count');

        return Inertia::render('Departements/Disciplines/Categories/Show', [
            'familles' => $familles,
            'category' => $category,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'criteres' => $criteres,
            'departement' => $departement,
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
