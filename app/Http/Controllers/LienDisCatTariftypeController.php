<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\ListeTarifType;
use Illuminate\Validation\Rule;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;

class LienDisCatTariftypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'type.id' =>  ['required', Rule::exists('liste_tarifs_types', 'id')],
            'nom' => ['nullable', 'string', 'min:3', 'max:255'],
        ]);

        $categorie->tarif_types()->create([
            'discipline_id' => $discipline->id,
            'tarif_type_id' => $request->type['id'],
            'nom' => $request->nom ?? $request->type['type'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Type de tarif ajouté');
    }

    /**
     * Display the specified resource.
     */
    public function show(LienDisCatTariftype $lienDisCatTariftype)
    {
        //
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
            'tarif_types',
            'tarif_types.tarif_attributs',
        ])
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->findOrFail($categorie->id);

        $categories = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'tarif_types',
            'tarif_types.tarif_attributs',
        ])->where('discipline_id', $discipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

        $listeTarifsTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type'])->get();

        return Inertia::render('Admin/Disciplines/Categories/Tarifs/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categorie' => $categorie,
            'categories' => $categories,
            'discipline' => $discipline,
            'listeTarifsTypes' => $listeTarifsTypes,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => 'required|string|min:3',
        ]);

        $tarifType->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Nom du type de tarif mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $tarifType->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Type de tarif supprimé');

    }
}