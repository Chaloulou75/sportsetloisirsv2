<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\Models\Categorie;
use App\Models\Structure;
use App\Models\StructureAddress;
use App\Models\ListDiscipline;
use App\Models\StructureDiscipline;
use App\Models\StructureActivite;
use App\Models\StructureCategorie;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;
use App\Models\StructureProduit;
use App\Models\StructureProduitCritere;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource and Show the form for creating a new resource.
     */
    public function index(Structure $structure)
    {
        $structure = Structure::select(['id', 'name', 'slug'])
                    ->where('id', $structure->id)
                    ->first();

        $structureActiviteCategories = StructureCategorie::with(['structure','categorie', 'discipline'])
            ->where('structure_id', $structure->id)
            ->latest()
            ->get();

        $actByDiscAndCategorie = $structureActiviteCategories->groupBy('discipline.name')->map(function ($disciplineCategories) {
            $categories = $disciplineCategories->groupBy('categorie.nom')->map(function ($categorieItems) {
                return [
                            'id' => $categorieItems->first()->id,
                            'name' => $categorieItems->first()->categorie->nom ?? 'Sans Catégorie',
                            'count' => $categorieItems->count()
                        ];
            })->sortByDesc('count');

            return [
                'name' => $disciplineCategories->first()->discipline->name,
                'count' => $disciplineCategories->count(),
                'categories' => $categories
            ];
        });

        $categories = Categorie::with('disciplines')->select(['id', 'nom', 'ico'])->get();
        $listDisciplines = ListDiscipline::with('categories')->select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Activites/Index', [
            'structure' => $structure,
            'categories' => $categories,
            'listDisciplines' => $listDisciplines,
            'structureActiviteCategories' => $structureActiviteCategories,
            'actByDiscAndCategorie' => $actByDiscAndCategorie,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Structure $structure)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $structure)
    {
        $validated = request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
        ]);

        $structure = Structure::where('id', $validated['structure_id'])->firstOrFail();

        $structureAdresse = StructureAddress::where('structure_id', $structure->id)->firstOrfail();

        // check if structure_id and discipline_id combined exists in StructureDiscipline
        $exists = StructureDiscipline::where('structure_id', $validated['structure_id'])
                                    ->where('discipline_id', $validated['discipline_id'])
                                    ->exists();
        if($exists) {
            return Redirect::back()->with('error', 'Cette discipline est déjà associée à cette structure.');
        }

        $structureDiscipline = StructureDiscipline::create([
            'structure_id' => $validated['structure_id'],
            'discipline_id' => $validated['discipline_id'],
        ]);
        $structureDiscipline->increment('nb_produits');

        // structureActiviteCategorie
        $validatedData = request()->validate([
            'categories_id' => ['required', 'array', Rule::exists('categories', 'id')]
        ]);

        foreach ($validatedData['categories_id'] as $category_id) {

            $lienActCat = LienDisciplineCategorie::where('discipline_id', $validated['discipline_id'])->where('categorie_id', $category_id)->firstOrfail();

            $validated['categorie_id'] = $lienActCat->id;
            $structureActiviteCategorie = StructureCategorie::create([
                'structure_id' => $validated['structure_id'],
                'discipline_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
            ]);

            $titre = $lienActCat->nom_categorie;

            $structureActivite = StructureActivite::create([
                'structure_id' => $validated['structure_id'],
                'discipline_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
                'titre' => $titre,
                'description' => "",
                'image' => "",
                "actif" => 1,
            ]);

            $declinaison = StructureProduit::create([
                'structure_id' => $validated['structure_id'],
                'discipline_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
                'activite_id' => $structureActivite->id,
                "actif" => 1,
                'lieu_id' => $structureAdresse->id,
                'reservable' => 0,
            ]);

            $activiteCategorieCriteres = LienDisciplineCategorieCritere::where('discipline_id', $validated['discipline_id'])->where('categorie_id', $validated['categorie_id'])->get();

            foreach($activiteCategorieCriteres as $activiteCategorieCritere) {
                $ActCatCriVal = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('activite_categorie_critere_id', $activiteCategorieCritere->id)->first();

                $critere = StructureProduitCritere::create([
                    'structure_id' => $validated['structure_id'],
                    'discipline_id' => $validated['discipline_id'],
                    'categorie_id' => $validated['categorie_id'],
                    'activite_id' => $structureActivite->id,
                    'declinaison_id' => $declinaison->id,
                    'critere_id' => $activiteCategorieCritere->id,
                    'valeur' => $ActCatCriVal->valeur ?? 'Tous',
                ]);
            }
        }


        return Redirect::route('structures.activites.index', $structure)->with('success', 'Activité créée, vous pouvez ajouter d\'autres activités à votre structure.');
    }

    /**
     * Show the form for editing a resource.
     */
    public function edit(Structure $structure, StructureCategorie $activite)
    {
        if (! Gate::allows('update-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission d\'éditer cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $structure = Structure::select(['id', 'name', 'slug'])->where('slug', $structure->slug)->first();
        $activite = StructureCategorie::with(['structure','categorie', 'discipline'])->where('structure_id', $structure->id)
                      ->where('activite_id', $activite->discipline->id)
                      ->where('categorie_id', $activite->categorie->id)
                      ->first();
        dd($activite);
        return Inertia::render('Structures/Activites/Edit', [
            'structure' => $structure,
            'activite' => $activite,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {

        if (! Gate::allows('update-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de modifier cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $validated= request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'nivel_id' => ['required', Rule::exists('nivels', 'id')],
            'publictype_id' => ['required', Rule::exists('publictypes', 'id')],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'description' => ['required', 'min:8'],
        ]);

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        // $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug;
        // $validated['structure_id'] = $structure->id;

        // $activite->update($validated);

        return Redirect::route('structures.show', $structure)->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure): RedirectResponse
    {
        // $activite = Activite::where('id', $activite->id)->first();

        // if (! Gate::allows('destroy-activite', $activite)) {
        //     return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de supprimer cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        // }
        // $structure = Structure::where('slug', $structure->slug)->firstOrFail();

        // $activite->delete();
        // sleep(0.5);

        return redirect()->route('structures.show', $structure)->with('success', 'Activite supprimée.');
    }
}
