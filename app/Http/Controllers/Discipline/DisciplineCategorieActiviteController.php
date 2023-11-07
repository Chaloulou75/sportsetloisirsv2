<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DisciplineCategorieActiviteController extends Controller
{
    public function show(ListDiscipline $discipline, $category, $activite, ?string $produit = null)
    {
        $selectedProduit = StructureProduit::where('id', request()->produit)->first();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $requestDiscipline = ListDiscipline::with('structureProduits')->where('slug', $discipline->slug)
                                    ->select(['id', 'name', 'slug', 'view_count'])
                                    ->first();

        $disciplinesSimilaires = $requestDiscipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
                ->where('discipline_id', $requestDiscipline->id)
                ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                ->withCount('structures_produits')
                ->orderByDesc('structures_produits_count')
                ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline) {
                $subquery->where('discipline_id', $requestDiscipline->id);
            });
        })
        ->select(['id', 'name', 'slug'])
        ->get();

        $requestCategory = LienDisciplineCategorie::where('discipline_id', $requestDiscipline->id)->where('slug', $category)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();

        $activite = StructureActivite::with([
                    'structure:id,name,slug,presentation_courte,presentation_longue,address,zip_code,city,country,address_lat,address_lng,user_id,structuretype_id,website,email,facebook,instagram,youtube,tiktok,phone1,phone2,date_creation,view_count,departement_id,logo',
                    'structure.creator:id,name',
                    'structure.users:id,name',
                    'structure.adresses'  => function ($query) {
                        $query->latest();
                    },
                    'structure.city:id,ville,ville_formatee,code_postal',
                    'structure.departement:id,departement,numero',
                    'structure.structuretype:id,name,slug',
                    'dates',
                    'instructeurs',
                    'discipline:id,name',
                    'categorie:id,categorie_id,discipline_id,nom_categorie_client',
                    'produits' => function ($query) {
                        $query->latest();
                    },
                    'produits.adresse',
                    'produits.criteres',
                    'produits.criteres.critere',
                    'produits.criteres.critere_valeur.sous_criteres.prodSousCritValeurs',
                    'produits.tarifs',
                    'produits.tarifs.tarifType',
                    'produits.tarifs.structureTarifTypeInfos',
                    'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
                    'produits.plannings',
                ])->find($activite);

        $produits = $activite->produits;

        $logoUrl = asset($activite->structure->logo);

        $criteres = LienDisciplineCategorieCritere::with([
            'valeurs' => function ($query) {
                $query->orderBy('defaut', 'desc');
            },
            'valeurs.sous_criteres',
            'valeurs.sous_criteres.sous_criteres_valeurs'
        ])
        ->whereIn('discipline_id', $activite->structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $activite->structure->categories->pluck('categorie_id'))
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
            'discipline' => $requestDiscipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'logoUrl' => $logoUrl,
            'activite' => $activite,
            'criteres' => $criteres,
            'activiteSimilaires' => $activiteSimilaires,
            'selectedProduit' => $selectedProduit,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'requestCategory' => $requestCategory,
            'produits' => $produits,
        ]);
    }
}
