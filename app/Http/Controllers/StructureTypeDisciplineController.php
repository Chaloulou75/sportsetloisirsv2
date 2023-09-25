<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
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
        $familles = Famille::withWhereHas('disciplines', function ($query) {
            $query->whereHas('structureProduits');
        })->select(['id', 'name', 'slug'])->get();

        $listDisciplines = ListDiscipline::whereHas('structureProduits')->select(['id', 'name', 'slug'])->get();

        $allCities = City::whereHas('produits')
                        ->select(['id', 'code_postal', 'ville', 'ville_formatee'])
                        ->get();

        $discipline = ListDiscipline::where('slug', $discipline->slug)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();
        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $structuretypeElected = Structuretype::where('id', $structuretype)->select(['id', 'name', 'slug'])->first();

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits')
                ->where('discipline_id', $discipline->id)
                ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->get();

        $categoriesWithoutProduit = LienDisciplineCategorie::whereDoesntHave('structures_produits')
        ->where('discipline_id', $discipline->id)
        ->select(['id', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->get();

        $criteres = LienDisciplineCategorieCritere::with('valeurs')->where('discipline_id', $discipline->id)->get();

        $structures = $discipline->structures()->with([
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
            'activites.discipline',
            'activites.categorie',
            'produits',
            'produits.criteres',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])->withCount('disciplines', 'produits', 'activites')
        ->whereHas('activites', function ($query) use ($discipline) {
            $query->where('discipline_id', $discipline->id);
        })
        ->paginate(12);

        $discipline->timestamp = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Structuretypes/Show', [
            'familles' => $familles,
            'categories' => $categories,
            'categoriesWithoutProduit' => $categoriesWithoutProduit,
            'structuretypeElected' => $structuretypeElected,
            'allStructureTypes' => $allStructureTypes,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'structures' => $structures,
            'discipline' => $discipline,
            'criteres' => $criteres,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
        ]);

    }
}
