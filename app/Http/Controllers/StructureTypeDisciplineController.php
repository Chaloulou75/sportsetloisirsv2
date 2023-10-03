<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class StructureTypeDisciplineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(ListDiscipline $discipline, $structuretype)
    {
        $familles = Famille::whereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();

        $discipline = ListDiscipline::with('structureProduits')->where('slug', $discipline->slug)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                                ->where('discipline_id', $discipline->id)
                                ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                                ->withCount('structures_produits')
                                ->orderByDesc('structures_produits_count')
                                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($discipline) {
            $query->whereHas('produits', function ($subquery) use ($discipline) {
                $subquery->where('discipline_id', $discipline->id);
            });
        })
                        ->select(['id', 'name', 'slug'])
                        ->get();

        $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();

        $criteres = LienDisciplineCategorieCritere::with('valeurs')->where('discipline_id', $discipline->id)->get();


        $structures = Structure::with([
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
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
        ])
        ->withCount('disciplines', 'produits', 'activites')
        ->whereHas('activites', function ($subquery) use ($discipline) {
            $subquery->where('discipline_id', $discipline->id);
        })->where('structuretype_id', $structuretypeElected->id)
        ->paginate(12);


        $produits = $discipline->structureProduits()->with([
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
        ])->whereHas('structure', function ($query) use ($structuretypeElected) {
            $query->where('structuretype_id', $structuretypeElected->id);
        })
        ->paginate(12);

        $discipline->timestamp = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Structuretypes/Show', [
            'familles' => $familles,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'structuretypeElected' => $structuretypeElected,
            'allStructureTypes' => $allStructureTypes,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'produits' => $produits,
            'structures' => $structures,
            'discipline' => $discipline,
            'criteres' => $criteres,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
        ]);

    }
}
