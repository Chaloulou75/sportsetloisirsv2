<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Nivel;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Publictype;
use App\Models\Departement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Mail\StructureCreated;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureTypeInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
                    'creator:id,name',
                    'users:id,name',
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                ])
                        ->filter(
                            request(['search'])
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
                            'user' => $structure->creator,
                            // 'disciplines' => $structure->activites->pluck('discipline.name')->unique(),
                            'logo' => $structure->logo ? asset('storage/'. $structure->logo) : 'https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
                ];
                        })->withQueryString(),
            'filters' => request()->all(['search']),
            'familles' => $familles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $structurestypes = Structuretype::with(['structuretypeattributs', 'structuretypeattributs.structuretypevaleurs'])->select(['id', 'name', 'slug'])->get();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Create', [
            'structurestypes' => $structurestypes,
            'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated= request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['nullable', 'url'],
            'phone1' => ['required', 'regex:/^0[1-9](?:[\-\s]?[0-9]{2}){4}$/'],
            'phone2' => ['nullable', 'regex:/^0[1-9](?:[\-\s]?[0-9]{2}){4}$/'],
            'facebook' => ['nullable', 'url', 'regex:/facebook\.com\/.*/i'],
            'instagram' => ['nullable', 'url', 'regex:/facebook\.com\/.*/i'],
            'youtube' => ['nullable', 'url', 'regex:/youtube\.com\/.*/i'],
            'tiktok' => ['nullable', 'url', 'regex:/tiktok\.com\/.*/i'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'date_creation' => ['nullable'],
            'presentation_courte' => ['required', 'min:8'],
            'presentation_longue' => ['nullable'],
            'abo_news' => ['nullable'],
            'abo_promo' => ['nullable'],
            'logo' => ['nullable','image','max:2048'],
        ]);

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        $validated['slug'] = $slug;

        $validated['user_id'] = auth()->id();

        $city= City::where('code_postal', $validated['zip_code'])->firstOrFail();
        $cityId = $city->id;
        $validated['city_id'] = $cityId;

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement= Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        $structure = Structure::create($validated);

        $newSlug = $structure->slug . '-' . $structure->id;
        $structure->update(['slug' => $newSlug]);

        if($request->file('logo')) {
            $path = $request->file('logo')->store('public/structures/' . $structure->id);
            $structure->update(['logo' => 'structures/' . $structure->id . '/' . $request->file('logo')->hashName()]);
        }

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

        $structure->users()->attach($structure->user_id, [
            'niveau' => 1,
            'contact' => $structure->creator->name,
            'email' => $structure->creator->email,
            'phone' => $structure->phone1,
        ]);

        $attributs = $request['attributs'];

        foreach ($attributs as $key => $attribut) {
            if (isset($attribut)) {
                StructureTypeInfo::create([
                    'attribut_id' => $key,
                    'valeur' => $attribut
                ]);
            }
        }

        Mail::to($structure->email)->send(new StructureCreated($structure));

        return Redirect::route('structures.activites.index', $structure->slug)->with('success', 'Votre structure est bien créée, vous allez recevoir une confirmation par email. Vous pouvez, maintenant, ajouter des activités à votre structure.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure)
    {
        $structure->timestamps = false;
        $structure->increment('view_count');

        $structure = Structure::with([
            'famille:id,name',
            'creator:id,name,email',
            'users:id,name',
            'cities:id,ville,ville_formatee',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
            ->where('slug', $structure->slug)
            ->first();

        $logoUrl = asset('storage/'.$structure->logo);

        // $disciplines = $structure->activites->pluck('discipline.name')->unique();

        return Inertia::render('Structures/Show', [
            'structure'=> $structure,
            // 'disciplines' => $disciplines,
            'logoUrl' => $logoUrl,
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

        $structurestypes = Structuretype::with(['structuretypeattributs', 'structuretypeattributs.structuretypevaleurs'])->select(['id', 'name', 'slug'])->get();
        $niveaux = Nivel::select(['id', 'name', 'slug'])->get();
        $publictypes = Publictype::select(['id', 'name', 'slug'])->get();
        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        $structure = Structure::with([
            'famille:id,name',
            'creator:id,name,email',
            'users:id,name',
            'cities:id,ville,ville_formatee',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            // 'activites' => function ($query) {
            //     $query->latest();
            // },
            // 'activites.discipline',
            // 'activites.nivel',
            // 'activites.publictype',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'abo_news', 'abo_promo','logo'])
            ->where('slug', $structure->slug)
            ->firstOrFail();

        return Inertia::render('Structures/Edit', [
            'structurestypes' => $structurestypes,
            'disciplines' => $disciplines,
            'niveaux' => $niveaux,
            'publictypes' => $publictypes,
            'structure' => $structure,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        $structure = Structure::where('id', $structure->id)->firstOrFail();

        $validated= request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['nullable', 'url'],
            'phone1' => ['required', 'regex:/^0[1-9](?:[\-\s]?[0-9]{2}){4}$/'],
            'phone2' => ['nullable', 'regex:/^0[1-9](?:[\-\s]?[0-9]{2}){4}$/'],
            'facebook' => ['nullable', 'url', 'regex:/facebook\.com\/.*/i'],
            'instagram' => ['nullable', 'url', 'regex:/facebook\.com\/.*/i'],
            'youtube' => ['nullable', 'url', 'regex:/youtube\.com\/.*/i'],
            'tiktok' => ['nullable', 'url', 'regex:/tiktok\.com\/.*/i'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'date_creation' => ['nullable'],
            'presentation_courte' => ['required', 'min:8'],
            'presentation_longue' => ['nullable'],
            'abo_news' => ['nullable'],
            'abo_promo' => ['nullable'],
            'logo' => ['nullable','image','max:2048'],
        ]);

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug . '-' . $structure->id;

        $city= City::where('code_postal', $validated['zip_code'])->firstOrFail();
        $cityId = $city->id;
        $validated['city_id'] = $cityId;

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement= Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        if($request->file('logo')) {
            request()->validate(['logo' => ['nullable','image','max:2048']]);
            if($structure->logo !== null) {
                Storage::disk('public')->delete('structures/' . $structure->id .'/'. $structure->logo);
            }
            $path = $request->file('logo')->store('public/structures/' . $structure->id);
            $structure->update(['logo' => 'structures/' . $structure->id . '/' . $request->file('logo')->hashName()]);
        }

        $structure->update($validated);

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
        $structureAddress = StructureAddress::where('structure_id', $structure->id)->firstOrFail();
        $structureAddress->update($validatedAddress);


        $attributs = $request['attributs'];

        foreach ($attributs as $key => $attribut) {
            if (isset($attribut)) {
                $structuretypeinfo = StructureTypeInfo::where('attribut_id', $key)->firstOrfail();
                $structuretypeinfo->update([
                    'attribut_id' => $key,
                    'valeur' => $attribut
                ]);
            }
        }

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

        if($structure->logo) {
            Storage::disk('public')->delete('public/structures/' . $structure->id .'/'. $structure->logo);
        }

        $structure->delete();
        sleep(0.5);

        return redirect()->route('structures.index')->with('success', 'Structure supprimée.');
    }
}
