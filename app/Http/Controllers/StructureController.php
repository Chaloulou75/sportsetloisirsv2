<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Nivel;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Discipline;
use App\Models\Publictype;
use App\Models\Departement;
use Illuminate\Support\Str;
use App\Models\Activitetype;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
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
        $familles = Famille::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Index', [
            'structures'=> Structure::with([
                    'famille:id,name',
                    'user:id,name',
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
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
                            request(['search', 'famille', 'discipline', 'localite'])
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
                            'tiktok' => $structure->tiktok,
                            'phone1' => $structure->phone1,
                            'phone2' => $structure->phone2,
                            'address' => $structure->address,
                            'zip_code' => $structure->zip_code,
                            'city' => $structure->city,
                            'country' => $structure->country,
                            'address_lat' => $structure->address_lat,
                            'address_lng' => $structure->address_lng,
                            'presentation_courte' => $structure->presentation_courte,
                            'presentation_longue' => $structure->presentation_longue,
                            'structuretype' => $structure->structuretype,
                            'departement_id' => $structure->departement_id,
                            // 'weekdays' => $structure->weekdays,
                            'user' => $structure->user,
                            'disciplines' => $structure->activites->pluck('discipline.name')->unique(),
                            // 'mediasImg' => MediaResource::collection($structure->medias),
                            // 'start_at' => $structure->start_at,
                            // 'end_at' => $structure->end_at,
                            // 'hour_start_at' => $structure->hour_start_at,
                            // 'hour_end_at' => $structure->hour_end_at,
                            'logo' => $structure->logo,
                ];
                        })->withQueryString(),
            'filters' => request()->all(['search', 'famille', 'discipline', 'localite']),
            'familles' => $familles,
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
            // 'famille_id' => ['nullable', Rule::exists('familles', 'id')],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['nullable', 'regex:'.$regex],
            'phone1' => ['required', 'regex:/^0[1-9](?:[\-\s]?[0-9]{2}){4}$/'],
            'phone2' => ['nullable', 'regex:/^0[1-9](?:[\-\s]?[0-9]{2}){4}$/'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'youtube' => ['nullable'],
            'tiktok' => ['nullable'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'presentation_courte' => ['required', 'min:8'],
            'presentation_longue' => ['nullable'],
            'logo' => ['nullable','image','max:2048'],
        ]);

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug;

        $city= City::where('code_postal', $validated['zip_code'])->firstOrFail();
        $cityId = $city->id;
        $validated['city_id'] = $cityId;

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement= Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        $structure = Structure::create($validated);

        $newSlug = $structure->slug . '-' . $structure->id;
        $structure->update(['slug' => $newSlug]);

        $path = $request->file('logo')->store('public/structures/' . $structure->id);
        $structure->update(['logo' => 'structures/' . $structure->id . '/' . $request->file('logo')->hashName()]);

        $validatedAddress = [
            'structure_id' => $structure->id,
            'name' => $structure->name,
            'address' => $structure->address,
            'zip_code' => $structure->zip_code,
            'city' => $structure->city,
            'country' => $structure->country,
            'city_id' => $structure->city_id,
            'country_id' => $structure->country_id,
            'address_lat' => $structure->address_lat,
            'address_lng' => $structure->address_lng,
            'phone' => $structure->phone1,
            'email' => $structure->email,
        ];

        $structureAddress = StructureAddress::create($validatedAddress);

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
            'famille:id,name',
            'user:id,name',
            'cities:id,ville,ville_formatee',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'activites' => function ($query) {
                $query->latest();
            },
            'activites.discipline',
            'activites.nivel',
            'activites.activitetype',
            'activites.publictype',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'view_count', 'departement_id', 'logo'])
            ->where('slug', $structure->slug)
            ->first();

        $logoUrl = asset('storage/'.$structure->logo);

        $disciplines = $structure->activites->pluck('discipline.name')->unique();

        // $clubLogoUrl = $structure->logo ? Storage::disk('s3')->temporaryUrl('logo/' .$structure->id. '/' .$structure->logo, now()->addMinutes(5)) : null;

        return Inertia::render('Structures/Show', [
            'structure'=> $structure,
            'disciplines' => $disciplines,
            'logoUrl' => $logoUrl,
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
        if (! Gate::allows('update-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission d\'éditer cette fiche, vous devez être le créateur de la structure ou un administrateur.');
        }

        $structurestypes = Structuretype::select(['id', 'name', 'slug'])->get();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $activitestypes = Activitetype::select(['id', 'name', 'slug'])->get();
        $disciplines = Discipline::select(['id', 'name', 'slug'])->get();

        $structure = Structure::with([
            'famille:id,name',
            'user:id,name',
            'cities:id,ville,ville_formatee',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'activites' => function ($query) {
                $query->latest();
            },
            'activites.discipline',
            'activites.nivel',
            'activites.activitetype',
            'activites.publictype',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'view_count', 'departement_id', 'logo'])
            ->where('slug', $structure->slug)
            ->firstOrFail();

        return Inertia::render('Structures/Edit', [
            'structurestypes' => $structurestypes,
            'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
            'activitestypes' => $activitestypes,
            'structure' => $structure,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $validated= request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['nullable', 'regex:'.$regex],
            'phone1' => ['required', 'digits:10'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'youtube' => ['nullable'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'presentation_courte' => ['required', 'min:8'],
        ]);

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug . '-' . $structure->id;

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement= Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        $structure->update($validated);

        return Redirect::route('structures.show', $structure->slug)->with('success', 'Votre structure a été mise à jour');

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
        sleep(0.5);

        return redirect()->route('structures.index')->with('success', 'Structure supprimée.');
    }
}
