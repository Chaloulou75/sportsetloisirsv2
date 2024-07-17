<?php

namespace App\Http\Controllers\City;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
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
use App\Http\Resources\StructuretypeResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\LienDisciplineCategorieCritere;
use App\Http\Resources\StructureProduitResource;
use App\Http\Resources\StructureActiviteResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;

class CityDisciplineActiviteController extends Controller
{
    public function show(Request $request, City $city, ListDiscipline $discipline, StructureActivite $activite, string $slug, ?string $produit = null): Response
    {

        $filters = $request->only(['crit', 'ssCrit']);
        $page = $request->input('page', 1);

        if ($produit !== null) {
            $selectedProduit = StructureProduitResource::make(StructureProduit::withRelations()->find($produit));
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

        $city = City::with(['structures', 'produits.adresse'])
                    ->withProductsAndDepartement()
                    ->withCount('produits')
                    ->withCount('structures')
                    ->find($city->id);

        $citiesAround = City::with('produits')->withCitiesAround($city)->get();

        $cityAroundIds = $citiesAround->pluck('id');

        $requestDiscipline = ListDiscipline::withProductsAndDisciplinesSimilaires()->find($discipline->id);

        $categories = LienDisciplineCategorie::withWhereHas('structures_produits.adresse', function (Builder $query) use ($city, $cityAroundIds) {
            $query->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
        })
            ->where('discipline_id', $requestDiscipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

        $firstCategories = $categories->take(4);
        $categoriesNotInFirst = $categories->diff($firstCategories);

        $allStructureTypes = StructureType::whereHas('structures', function ($query) use ($requestDiscipline, $city, $cityAroundIds) {
            $query->whereHas('produits', function ($subquery) use ($requestDiscipline, $city, $cityAroundIds) {
                $subquery->where('discipline_id', $requestDiscipline->id)
                        ->whereHas('adresse', function ($addressQuery) use ($city, $cityAroundIds) {
                            $addressQuery->where('city_id', $city->id)->orWhereIn('city_id', $cityAroundIds);
                        });
            });
        })
                ->select(['id', 'name', 'slug'])
                ->get();

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
            'name' => 'villes.disciplines.activites.show',
            'params' => [
                'city' => $city,
                'discipline' => $discipline,
                'activite' => $activite->id,
                'slug' => $activite->slug_title,
            ]
        ];

        return Inertia::render('Structures/Activites/Show', [
            'produits' => fn () => StructureProduitResource::collection($produits),
            'familles' => fn () => FamilleResource::collection($familles),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'allCities' => fn () => CityResource::collection($allCities),
            'activite' => fn () => StructureActiviteResource::make($activite),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'city' => fn () => CityResource::make($city),
            'citiesAround' => fn () => CityResource::collection($citiesAround),
            'discipline' => fn () => ListDisciplineResource::make($requestDiscipline),
            'activiteSimilaires' => fn () => StructureActiviteResource::collection($activiteSimilaires),
            'selectedProduit' => fn () => $selectedProduit ?? null,
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'firstCategories' => fn () => LienDisciplineCategorieResource::collection($firstCategories),
            'categoriesNotInFirst' => fn () => LienDisciplineCategorieResource::collection($categoriesNotInFirst),
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'filters' => $filters ?? null,
            'currentRoute' => $currentRoute,
        ]);
    }
}
