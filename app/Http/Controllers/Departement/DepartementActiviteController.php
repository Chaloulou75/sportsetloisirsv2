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
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorieCritere;

class DepartementActiviteController extends Controller
{
    public function show(Departement $departement, StructureActivite $activite, ?string $produit = null): Response
    {
        $selectedProduit = StructureProduit::withRelations()->find(request()->produit);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $departement = Departement::withCitiesAndRelations()
                ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                ->find($departement->id);

        $activite = StructureActivite::with([
            'structure',
            'structure.adresses',
            'instructeurs'
        ])->find($activite->id);

        $produits = $activite->produits()->withRelations()->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $activite->discipline_id)
                ->where('categorie_id', $activite->categorie_id)
                ->where('visible_front', true)
                ->get();

        $activiteSimilaires = StructureActivite::with([
                'produits',
                'produits.criteres',
                'produits.criteres.sous_criteres',
                'produits.adresse'
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
                    'departement' => fn () => $departement,
                    'produits' => fn () => $produits,
                    'familles' => fn () => FamilleResource::collection($familles),
                    'listDisciplines' => fn () => $listDisciplines,
                    'allCities' => fn () => CityResource::collection($allCities),
                    'activite' => fn () => $activite,
                    'criteres' => fn () => $criteres,
                    'activiteSimilaires' => fn () => $activiteSimilaires,
                    'selectedProduit' => fn () => $selectedProduit,
        ]);
    }
}
