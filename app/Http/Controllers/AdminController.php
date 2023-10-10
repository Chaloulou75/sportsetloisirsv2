<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Critere;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $listDisciplines = ListDiscipline::select(['id', 'slug', 'name'])->get();
        $categories = Categorie::select(['id', 'nom'])->get();

        $structures = Structure::select(['id', 'name', 'slug'])->get();

        $users = User::select(['id', 'name', 'email'])->paginate(12);

        return Inertia::render('Admin/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categories' => $categories,
            'listDisciplines' => $listDisciplines,
            'structures' => $structures,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->where('id', $discipline->id)->firstOrFail();
        $disciplineFamillesIds = $discipline->familles()->select('famille_id')
            ->pluck('famille_id');

        $categories = LienDisciplineCategorie::with(['discipline', 'categorie'])->where('discipline_id', $discipline->id)->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])->get();

        $categoriesIds = $categories->pluck('categorie_id');

        $otherCategories = Categorie::select('id', 'nom')->whereNotIn('id', $categoriesIds)->get();

        $disciplinesSimilaires = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id', 'name', 'slug', 'famille')
            ->get();

        $disciplinesSimilairesIds = $discipline->disciplinesSimilaires()
            ->select('discipline_similaire_id')
            ->pluck('discipline_similaire_id');

        $listDisciplines = ListDiscipline::select(['id', 'slug', 'name'])->whereNotIn('id', $disciplinesSimilairesIds)->whereNot('id', $discipline->id)->paginate(12);

        $familles = Famille::select('id', 'name', 'slug', 'nom_long')->whereNotIn('id', $disciplineFamillesIds)->get();

        $disciplineCategorieCriteres = LienDisciplineCategorieCritere::with([
            'discipline', 'categorie', 'critere', 'valeurs',
        ])->where('discipline_id', $discipline->id)
        ->orderBy('categorie_id')
        ->get();

        if($disciplineCategorieCriteres->isEmpty()) {
            $groupedData = collect();
        } else {
            $groupedData = $disciplineCategorieCriteres->groupBy(function ($item) {
                return $item->categorie->id;
            })
            ->map(function ($categoryItems) {
                $firstItem = $categoryItems->first();

                $criteres = $categoryItems->groupBy(function ($item) {
                    return $item->critere->id;
                })
                ->map(function ($criterionItems) {
                    $firstCriterionItem = $criterionItems->first();

                    return [
                        'disciplineCategorieCritere' => $firstCriterionItem->id,
                        'critere' => $firstCriterionItem->critere,
                        'valeurs' => $criterionItems->flatMap->valeurs,
                    ];
                });

                return [
                    'categorie' => $firstItem->categorie,
                    'categoriebase' => [
                        'id' => $firstItem->categorie->categorie->id,
                        'nom' => $firstItem->categorie->categorie->nom,
                    ],
                    'criteres' => $criteres,
                ];
            });
        }

        $listeCriteres = Critere::select(['id', 'nom'])->get();

        return Inertia::render('Admin/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categories' => $categories,
            'otherCategories' => $otherCategories,
            'discipline' => $discipline,
            'disciplinesSimilaires' => $disciplinesSimilaires,
            'listDisciplines' => $listDisciplines,
            'familles' => $familles,
            'disciplineCategorieCriteres' => $disciplineCategorieCriteres,
            'groupedData' => $groupedData,
            'listeCriteres' => $listeCriteres,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
