<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Departement;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorieCritere;

class DepartementActiviteController extends Controller
{
    public function show(Departement $departement, $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::where('id', request()->produit)->first();

        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();


        $departement = Departement::with(['cities' => function ($query) {
            $query->withWhereHas('produits');
        }])
                ->where('slug', $departement->slug)
                ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                ->first();

        $activite = StructureActivite::withRelations()->find($activite);

        $produits = $activite->produits;

        $logoUrl = asset($activite->structure->logo);


        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
                ->whereIn('discipline_id', $activite->structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $activite->structure->categories->pluck('categorie_id'))
                ->get();

        $activiteSimilaires = StructureActivite::withRelations()->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
                    'departement' => $departement,
                    'produits' => $produits,
                    'familles' => $familles,
                    'listDisciplines' => $listDisciplines,
                    'allCities' => $allCities,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'activiteSimilaires' => $activiteSimilaires,
                    'selectedProduit' => $selectedProduit,
        ]);
    }
}
