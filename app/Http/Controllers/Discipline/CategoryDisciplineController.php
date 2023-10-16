<?php

namespace App\Http\Controllers\Discipline;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class CategoryDisciplineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(ListDiscipline $discipline, $category)
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $discipline = ListDiscipline::with('structureProduits')->where('slug', $discipline->slug)
                            ->select(['id', 'name', 'slug', 'view_count'])
                            ->first();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $category = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('slug', $category)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->first();

        $categories = LienDisciplineCategorie::whereHas('structures_produits')
            ->where('discipline_id', $discipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
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

        $criteres = LienDisciplineCategorieCritere::with('valeurs')->where('discipline_id', $discipline->id)->where('categorie_id', $category->id)->get();

        $structures = Structure::with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,slug,ville,ville_formatee,code_postal',
            'structuretype:id,name,slug',
            'activites' => function ($query) use ($discipline, $category) {
                $query->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
            },
            'activites.discipline:id,name,slug',
            'activites.categorie:id,discipline_id,categorie_id,nom_categorie_pro,nom_categorie_client',
        ])
        ->whereHas('activites', function ($subquery) use ($discipline, $category) {
            $subquery->where('discipline_id', $discipline->id)->where('categorie_id', $category->id);
        })
        ->paginate(12);

        $produits = $discipline->structureProduits()->with([
            'structure:id,name',
            'adresse',
            'discipline:id,name,slug',
            'activite:id,titre',
            'criteres:id,activite_id,produit_id,critere_id,valeur',
            'criteres.critere:id,nom',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ])->where('categorie_id', $category->id)
        ->paginate(12);

        $discipline->timestamp = false;
        $discipline->increment('view_count');

        return Inertia::render('Disciplines/Categories/Show', [
            'familles' => $familles,
            'category' => $category,
            'categories' => $categories,
            'firstCategories' => $firstCategories,
            'categoriesNotInFirst' => $categoriesNotInFirst,
            'allStructureTypes' => $allStructureTypes,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'discipline' => $discipline,
            'criteres' => $criteres,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'produits' => $produits,
            'structures' => $structures,
        ]);

    }

    public function store(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorieNotInId = $request->input('categorieNotIn');
        $categorieNotIn = Categorie::findOrFail($categorieNotInId);
        $discipline->categories()->attach($categorieNotIn);

        return to_route('admin.edit', $discipline)->with('success', 'Catégorie ajoutée');
    }

    /**
     * detach a resource in storage.
     */
    public function detach(Request $request, ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorieInId = $request->input('categorieIn');
        $categorieIn = Categorie::findOrFail($categorieInId);

        $disciplineCategorie = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('categorie_id', $categorieIn->id)->first();
        $discCatCrit = LienDisciplineCategorieCritere::with('valeurs')->where('categorie_id', $disciplineCategorie->id)->get();


        foreach ($discCatCrit as $item) {
            if ($item->valeurs->isNotEmpty()) {
                foreach ($item->valeurs as $valeur) {
                    $valeur->delete();
                }
            }
        }


        if($discCatCrit->isNotEmpty()) {
            foreach($discCatCrit as $critere) {
                $critere->delete();
            }
        }

        $discipline->categories()->detach($categorieIn);

        return to_route('admin.edit', $discipline)->with('success', 'Catégorie supprimée, ainsi que les critères et valeurs associées à cette catégorie.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline)
    {
        $request->validate([
            'nom_categorie_client' => ['required', 'string', 'max:255'],
            'nom_categorie_pro' => ['required', 'string', 'max:255'],
            'id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
        ]);

        $categorieDiscipline = LienDisciplineCategorie::where('id', $request->id)->firstOrFail();

        $categorieDiscipline->update([
            'nom_categorie_client' => $request->nom_categorie_client,
            'nom_categorie_pro' => $request->nom_categorie_pro,
        ]);
        return to_route('admin.edit', $discipline)->with('success', 'Catégorie mise à jour');
    }
}