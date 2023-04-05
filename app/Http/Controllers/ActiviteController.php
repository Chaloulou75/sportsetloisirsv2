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
use App\Models\Structuretype;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class ActiviteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Structure $structure)
    {
        $structure = Structure::select(['id', 'name', 'slug'])->where('slug', $structure->slug)->get();
        $structurestypes = Structuretype::select(['id', 'name'])->get();
        $niveaux = Nivel::select(['id', 'name'])->get();
        $publictypes = Publictype::select(['id', 'name'])->get();
        $activitestypes = Activitetype::select(['id', 'name'])->get();
        $disciplines = Discipline::select(['id', 'name'])->get();

        return Inertia::render('Structures/Activites/Create', [
            'structure' => $structure,
            'structurestypes' => $structurestypes,
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
        dd($request->all());

        $validated= request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'discipline_id' => ['required', Rule::exists('disciplines', 'id')],
            'activitetype_id' => ['required', Rule::exists('activitestypes', 'id')],
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
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug;
        $validated['structure_id'] = $structure->id;

        $activite = Activite::create($validated);

        // $disciplinesIds = collect($request['disciplines'])->pluck('id');
        // $structure->disciplines()->attach($discipline_id);

        return Redirect::route('structures.index')->with('success', 'Activité crée, ajoutez des activités à votre structure.');
    }
}
