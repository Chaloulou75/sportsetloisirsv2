<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nivel;
use App\Models\Category;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Publictype;
use Illuminate\Support\Str;
use App\Models\Activitetype;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Index', [
            'structures'=> Structure::with([
                    'category:id,name',
                    'user:id,name',
                    'disciplines:id,name',
                    'cities:id,ville,ville_formatee',
                    'departements:id,departement,numero',
                    'structuretype:id,name,slug',
                    'activites:id,name,slug,structure_id,description,address,city,zip_code,country,address_lat,address_lng,discipline_id,nivel_id,activitetype_id,publictype_id',
                    'activites.discipline',
                    'activites.nivel',
                    'activites.activitetype',
                    'activites.publictype',
                    // 'weekdays:id,name',
                    // 'medias',
                ])
                        ->filter(
                            request(['search', 'category', 'discipline'])
                        )
                        ->latest()
                        ->paginate(9)
                        ->through(function ($structure) {
                            return [
                            'id' => $structure->id,
                            'name' => $structure->name,
                            'slug' => $structure->slug,
                            'website' => $structure->website,
                            'email' => $structure->email,
                            'facebook' => $structure->facebook,
                            'instagram' => $structure->instagram,
                            'youtube' => $structure->youtube,
                            'phone' => $structure->phone,
                            'address' => $structure->address,
                            'zip_code' => $structure->zip_code,
                            'city' => $structure->city,
                            'country' => $structure->country,
                            'address_lat' => $structure->address_lat,
                            'address_lng' => $structure->address_lng,
                            'description' => $structure->description,
                            'structuretype' => $structure->structuretype,
                            // 'disciplines' => $structure->disciplines,
                            // 'weekdays' => $structure->weekdays,
                            'user' => $structure->user,
                            'disciplines' => $structure->activites->pluck('discipline.name')->unique(),
                            // 'mediasImg' => MediaResource::collection($structure->medias),
                            // 'start_at' => $structure->start_at,
                            // 'end_at' => $structure->end_at,
                            // 'hour_start_at' => $structure->hour_start_at,
                            // 'hour_end_at' => $structure->hour_end_at,
                            // 'logo' => $structure->logo ? Storage::disk('s3')->temporaryUrl('logo/' .$structure->id. '/' .$structure->logo, now()->addMinutes(5)) : null,
                ];
                        })->withQueryString(),
            'filters' => request()->all(['search', 'category', 'discipline']),
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $structurestypes = Structuretype::select(['id', 'name', 'slug'])->get();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $activitestypes = Activitetype::select(['id', 'name', 'slug'])->get();
        $disciplines = Discipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Create', [
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
    public function store(Request $request)
    {
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $validated= request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
            // 'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['nullable', 'regex:'.$regex],
            'phone' => ['required', 'digits:10'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'youtube' => ['nullable'],
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

        $structure = Structure::create($validated);

        // $disciplinesIds = collect($request['disciplines'])->pluck('id');
        // $structure->disciplines()->attach($disciplinesIds);

        return Redirect::route('activites.create', $structure->slug)->with('success', 'Structure crée, maintenant, ajoutez des activités à votre structure.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure)
    {
        $structure->timestamps = false;
        $structure->increment('view_count');

        // $mediasImg = MediaResource::collection(Media::with('club')->where('club_id', $club->id)->latest()->get());

        $structure = Structure::with([
            'category:id,name',
            'user:id,name',
            'disciplines:id,name',
            'cities:id,ville,ville_formatee',
            'departements:id,departement,numero',
            'structuretype:id,name,slug',
            'activites:id,name,slug,structure_id,description,address,city,zip_code,country,address_lat,address_lng,discipline_id,nivel_id,activitetype_id,publictype_id',
            'activites.discipline',
            'activites.nivel',
            'activites.activitetype',
            'activites.publictype',
            ])
            ->select(['id', 'name', 'slug', 'description', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'phone', 'view_count'])
            ->where('slug', $structure->slug)
            ->first();

        $disciplines = $structure->activites->pluck('discipline.name')->unique();

        // $clubLogoUrl = $structure->logo ? Storage::disk('s3')->temporaryUrl('logo/' .$structure->id. '/' .$structure->logo, now()->addMinutes(5)) : null;

        return Inertia::render('Structures/Show', [
            'structure'=> $structure,
            'disciplines' => $disciplines,
            // 'clubLogoUrl' => $clubLogoUrl,
            // 'mediasImg' => MediaResource::collection($club->medias),
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure): RedirectResponse
    {
        $structure = Structure::where('id', $structure->id)->first();

        if (! Gate::allows('destroy-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de supprimer cette fiche, vous devez être le créateur de la structure ou un administrateur.');
        }
        // $structure = Structure::where('slug', $structure->slug)->firstOrFail();

        $structure->delete();
        sleep(1);

        return redirect()->route('structures.index')->with('success', 'Structure supprimée.');
    }
}
