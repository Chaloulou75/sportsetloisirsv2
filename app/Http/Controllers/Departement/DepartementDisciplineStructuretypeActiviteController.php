<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\FamilleResource;
use App\Models\LienDisciplineCategorie;
use App\Http\Resources\DepartementResource;
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureActiviteResource;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class DepartementDisciplineStructuretypeActiviteController extends Controller
{
    public function show(Request $request, Departement $departement, ListDiscipline $discipline, StructureType $structuretype, StructureActivite $activite, ?string $produit = null): Response
    {
        $filters = $request->only(['crit', 'ssCrit']);
        $page = $request->input('page', 1);

        if ($produit !== null) {
            $selectedProduit = StructureProduit::withRelations()->find(request()->produit);
        }

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $departement = Departement::withCitiesAndRelations()
                ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                ->find($departement->id);

        $categories = LienDisciplineCategorie::whereHas('structures_produits.adresse', function ($query) use ($departement) {
            $query->whereIn('city_id', $departement->cities->pluck('id'));
        })
                        ->where('discipline_id', $requestDiscipline->id)
                        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
                        ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline, $departement) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline, $departement) {
                $subquery->where('discipline_id', $requestDiscipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($departement) {
                            $addressQuery->whereIn('city_id', $departement->cities->pluck('id'));
                        });
            });
        })
                        ->select(['id', 'name', 'slug'])
                        ->get();

        $structuretypeElected = Structuretype::select(['id', 'name', 'slug'])->find($structuretype->id);

        $activite = StructureActivite::with([
            'structure',
            'structure.adresses',
            'instructeurs'
        ])->find($activite->id);

        $produits = $activite->produits()->withRelations()->filter($filters)->paginate(4);

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->where('discipline_id', $requestDiscipline->id)
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


        $currentRoute = [
                    'name' => 'departements.disciplines.structuretypes.activites.show',
                    'params' => [
                        'departement' => $departement,
                        'discipline' => $discipline,
                        'structuretype' => $structuretype,
                        'activite' => $activite->id,
                        'slug' => $activite->slug_title,
                    ]
                ];


        return Inertia::render('Structures/Activites/Show', [
            'departement' => fn () => DepartementResource::make($departement),
            'discipline' => fn () => ListDisciplineResource::make($requestDiscipline) ,
            'produits' => fn () => StructureProduitResource::collection($produits),
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'activite' => fn () => StructureActiviteResource::make($activite) ,
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'activiteSimilaires' => fn () => StructureActiviteResource::collection($activiteSimilaires),
            'selectedProduit' => fn () => StructureProduitResource::make($selectedProduit),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories) ,
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories) ,
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'structuretypeElected' => fn () => StructuretypeResource::make($structuretypeElected),
            'filters' => $filters ?? null,
            'currentRoute' => $currentRoute,
        ]);

    }
}
