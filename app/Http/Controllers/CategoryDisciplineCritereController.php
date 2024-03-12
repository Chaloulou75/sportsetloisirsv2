<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Critere;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;

class CategoryDisciplineCritereController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'critere.id' => ['required', Rule::exists('liste_criteres', 'id')],
            'categorie.id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'type_champ.type' => ['required', 'string', 'max:255'],
            'nom' => ['nullable', 'string'],
        ]);

        $discCat = LienDisciplineCategorie::with(['discipline:id,name,slug'])->findOrFail($request->categorie['id']);

        LienDisciplineCategorieCritere::create([
            "discipline_id" => $discCat->discipline_id,
            "categorie_id" => $request->categorie['id'],
            "critere_id" => $request->critere['id'],
            "nom" => $request->nom ?? $request->critere['nom'],
            "type_champ_form" => $request->type_champ['type'],
            "visible_back" => true,
            "visible_front" => true,
            "visible_block" => true,
            "indexable" => false,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCat->discipline->slug, 'categorie' => $discCat])->with('success', 'Critère ajouté avec succès');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline, LienDisciplineCategorie $categorie): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        $categorie = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'criteres' => function ($query) {
                $query->orderBy('ordre');
            },
            'criteres.critere',
            'criteres.valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
            'criteres.valeurs.sous_criteres',
            'criteres.valeurs.sous_criteres.sous_criteres_valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
        ])
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->findOrFail($categorie->id);

        $categories = LienDisciplineCategorie::with([
                'discipline',
                'categorie',
                'criteres',
                'criteres.critere',
                'criteres.valeurs',
                'criteres.valeurs.sous_criteres',
                'criteres.valeurs.sous_criteres.sous_criteres_valeurs'
            ])
            ->where('discipline_id', $discipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

        $listeCriteres = Critere::select(['id', 'nom'])->get();

        return Inertia::render('Admin/Disciplines/Categories/Criteres/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categorie' => fn () => $categorie,
            'categories' => fn () => $categories,
            'discipline' => fn () => $discipline,
            'listeCriteres' => fn () => $listeCriteres,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisciplineCategorieCritere $critere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'visible_front' => 'required|boolean:0,1,true,false',
            'visible_back' => 'required|boolean:0,1,true,false',
            'visible_block' => 'required|boolean:0,1,true,false',
            'ordre' => 'nullable|numeric',
            'indexable' => 'nullable|boolean:0,1,true,false',
        ]);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie', 'valeurs'])->findOrFail($critere->id);

        $discCatCritere->update([
            'visible_front' => $request->visible_front,
            'visible_back' => $request->visible_back,
            'visible_block' => $request->visible_block,
            'ordre' => $request->ordre,
            'indexable' => $request->indexable,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Visibilité du critère mise à jour');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatename(Request $request, LienDisciplineCategorieCritere $critere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => 'required|string|min:3',
        ]);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie', 'valeurs'])->findOrFail($critere->id);

        $discCatCritere->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Nom du critère mis à jour');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritere $lienDisciplineCategorieCritere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie', 'valeurs'])->findOrFail($lienDisciplineCategorieCritere->id);

        if($discCatCritere->valeurs->isNotEmpty()) {
            foreach ($discCatCritere->valeurs as $valeur) {
                $valeur->delete();
            }
        }

        $discCatCritere->delete();
        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Critère et valeurs associés supprimés');

    }
}
