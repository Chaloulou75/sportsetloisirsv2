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

class CityDisciplineCategorieStructureController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(City $city, $discipline, $category, $structure)
    {
        $familles = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })->select(['id', 'name', 'slug'])->get();

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

        $citiesAround = City::with('structures')
                    ->select('id', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon')
                    ->selectRaw("(6366 * acos(cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) + sin(radians({$city->latitude})) * sin(radians(latitude)))) AS distance")
                    ->whereHas('structures')
                    ->where('id', '!=', $city->id)
                    ->havingRaw('distance <= ?', [$city->tolerance_rayon])
                    ->orderBy('distance', 'ASC')
                    ->limit(10)
                    ->get();

        // pour disciplines => function ($query) use ($category) {
        //         $query->where('categorie_id', $category->id);
        //     }
        // pour categories => function ($query) use ($discipline) {
        //     $query->where('discipline_id', $discipline->id);
        // }
        // pour activites => function ($query) use ($discipline, $category) {
        //         $query->where('discipline_id', $discipline->id)
        //             ->where('categorie_id', $category->id);
        //     }

        $structure = Structure::with([
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
            'activites' ,
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
        ])->where('slug', $structure)
        ->withCount('disciplines', 'activites', 'produits')
        ->whereHas('activites', function ($query) use ($discipline, $category) {
            $query->where('discipline_id', $discipline->id)
                ->where('categorie_id', $category->id);
        })
        ->first();


        $logoUrl = asset($structure->logo);

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
        ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
        ->get();

        // dd($criteres);

        $structure->timestamp = false;
        $structure->increment('view_count');

        return Inertia::render('Villes/Disciplines/Categories/Structures/Show', [
            'familles' => $familles,
            'category' => $category,
            'categories' => $categories,
            'criteres' => $criteres,
            'allStructureTypes' => $allStructureTypes,
            'city' => $city,
            'citiesAround' => $citiesAround,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'structure' => $structure,
            'logoUrl' => $logoUrl,
            'discipline' => $discipline,
        ]);

    }

}
