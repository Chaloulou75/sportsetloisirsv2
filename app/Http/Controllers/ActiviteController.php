<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nivel;
use App\Models\Activite;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Publictype;
use Illuminate\Support\Str;
use App\Models\Activitetype;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ActiviteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Structure $structure)
    {
        $structure = Structure::select(['id', 'name', 'slug'])->where('slug', $structure->slug)->first();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $activitestypes = Activitetype::select(['id', 'name', 'slug'])->get();
        $disciplines = Discipline::select(['id', 'name', 'slug'])->get();

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
        $validated= request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'discipline_id' => ['required', Rule::exists('disciplines', 'id')],
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

        $activite = Activite::create($validated);

        return Redirect::route('structures.show', $structure)->with('success', 'Activité crée, ajoutez d\'autres activités à votre structure.');
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
        $disciplines = Discipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Activites/Edit', [
            'structure' => $structure,
            'activite' => $activite,
            'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
            'activitestypes' => $activitestypes,
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
            'discipline_id' => ['required', Rule::exists('disciplines', 'id')],
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
