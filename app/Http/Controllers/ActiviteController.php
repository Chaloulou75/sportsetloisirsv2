<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nivel;
use App\Models\Activite;
use App\Models\Categorie;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Publictype;
use Illuminate\Support\Str;
use App\Models\Activitetype;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureActivite;
use App\Models\StructureDiscipline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\LienActiviteCategorie;
use Illuminate\Http\RedirectResponse;
use App\Models\StructureActiviteProduit;
use Illuminate\Support\Facades\Redirect;
use App\Models\StructureActiviteCategorie;
use App\Models\LienActiviteCategorieCritere;
use App\Models\LienActiviteCategorieCritereValeur;
use App\Models\StructureActiviteProduitDeclinaison;
use App\Models\StructureActiviteProduitDeclinaisonCritere;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource and Show the form for creating a new resource.
     */
    public function index(Structure $structure)
    {
        $structure = Structure::with(['activites',
                        'activites.discipline',
                        'activites.categories',
                        'activites.nivel',
                        'activites.activitetype',
                        'activites.publictype'
                    ])
                    ->select(['id', 'name', 'slug'])
                    ->where('id', $structure->id)
                    ->first();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        // $activitestypes = Activitetype::select(['id', 'name', 'slug'])->get();
        // $disciplines = Discipline::select(['id', 'name', 'slug'])->get();
        $categories = Categorie::with('listactivites')->select(['id', 'nom', 'ico'])->get();
        $listDisciplines = ListDiscipline::with('categories')->select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Activites/Index', [
            'structure' => $structure,
            // 'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
            // 'activitestypes' => $activitestypes,
            'categories' => $categories,
            'listDisciplines' => $listDisciplines,
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
        $structure = Structure::with([
                    'activites',
                    'activites.discipline',
                    'activites.nivel',
                    'activites.activitetype',
                    'activites.publictype'
                ])->select(['id', 'name', 'slug'])
                ->where('slug', $structure->slug)
                ->first();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $activitestypes = Activitetype::select(['id', 'name', 'slug'])->get();
        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Activites/Create', [
            'structure' => $structure,
            'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
            'activitestypes' => $activitestypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $structure)
    {
        $validated = request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'nivel_id' => ['required'],
            'publictype_id' => ['required'],
        ]);

        $structure = Structure::where('id', $validated['structure_id'])->firstOrFail();

        $structureAdresse = StructureAddress::where('structure_id', $structure->id)->firstOrfail();

        // structureActivite
        $structureActivite = StructureActivite::create([
            'structure_id' => $validated['structure_id'],
            'activite_id' => $validated['discipline_id'],
        ]);
        $structureActivite->increment('nb_produits');

        // structureActiviteCategorie
        $validatedData = request()->validate([
            'categories_id' => ['required', 'array', Rule::exists('categories', 'id')]
        ]);

        foreach ($validatedData['categories_id'] as $category_id) {

            $lienActCat = LienActiviteCategorie::where('id_activite', $validated['discipline_id'])->where('id_categorie', $category_id)->firstOrfail();

            $validated['categorie_id'] = $lienActCat->id;
            $structureActiviteCategorie = StructureActiviteCategorie::create([
                'structure_id' => $validated['structure_id'],
                'activite_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
            ]);

            $titre = $lienActCat->nom_categorie;

            $structureActiviteProduit = StructureActiviteProduit::create([
                'structure_id' => $validated['structure_id'],
                'activite_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
                'titre' => $titre,
                'description' => "",
                'image' => "",
                "actif" => 1,
            ]);

            $declinaison = StructureActiviteProduitDeclinaison::create([
                'structure_id' => $validated['structure_id'],
                'activite_id' => $validated['discipline_id'],
                'categorie_id' => $validated['categorie_id'],
                'produit_id' => $structureActiviteProduit->id,
                "actif" => 1,
                'lieu_id' => $structureAdresse->id,
                'reservable' => 0,
            ]);

            $ActiviteCategorieCriteres = LienActiviteCategorieCritere::where('activite_id', $validated['discipline_id'])->where('categorie_id', $validated['categorie_id'])->get();

            foreach($ActiviteCategorieCriteres as $ActiviteCategorieCritere) {
                $ActCatCriVal = LienActiviteCategorieCritereValeur::where('defaut', 1)->where('activite_categorie_critere_id', $ActiviteCategorieCritere->id)->get();

                $critere = StructureActiviteProduitDeclinaisonCritere::create([
                    'structure_id' => $validated['structure_id'],
                    'activite_id' => $validated['discipline_id'],
                    'categorie_id' => $validated['categorie_id'],
                    'produit_id' => $structureActiviteProduit->id,
                    'declinaison_id' => $declinaison->id,
                    'critere_id' => $ActiviteCategorieCritere->id,
                    'valeur' => $ActCatCriVal->valeur,
                ]);
            }
        }


        return Redirect::route('structures.activites.index', $structure)->with('success', 'Activité créée, vous pouvez ajouter d\'autres activités à votre structure.');
    }

    /**
     * Show the form for editing a resource.
     */
    public function edit(Structure $structure, Activite $activite)
    {
        if (! Gate::allows('update-activite', $activite)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission d\'éditer cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $structure = Structure::select(['id', 'name', 'slug'])->where('slug', $structure->slug)->first();
        $activite = Activite::where('slug', $activite->slug)->first();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $activitestypes = Activitetype::select(['id', 'name', 'slug'])->get();
        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Activites/Edit', [
            'structure' => $structure,
            'activite' => $activite,
            'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
            'activitestypes' => $activitestypes,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Structure $structure, Activite $activite)
    {

        if (! Gate::allows('update-activite', $activite)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de modifier cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $validated= request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'activitetype_id' => ['required', Rule::exists('activitetypes', 'id')],
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

        $activite->update($validated);

        return Redirect::route('structures.show', $structure)->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, Activite $activite): RedirectResponse
    {
        $activite = Activite::where('id', $activite->id)->first();

        if (! Gate::allows('destroy-activite', $activite)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de supprimer cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }
        // $structure = Structure::where('slug', $structure->slug)->firstOrFail();

        $activite->delete();
        sleep(0.5);

        return redirect()->route('structures.show', $structure)->with('success', 'Activite supprimée.');
    }
}
