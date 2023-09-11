<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\StructureUser;
use App\Mail\StructureCreated;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\StructureTypeInfo;
use App\Models\ProductReservation;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\LienDisciplineCategorieCritere;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $familles = Famille::with([
            'disciplines' => function ($query) {
                $query->whereHas('structures');
            }
        ])
        ->whereHas('disciplines', function ($query) {
            $query->whereHas('structures');
        })->select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Index', [
            'structures' => Structure::with([
                    'adresses'  => function ($query) {
                        $query->latest();
                    },
                    'creator:id,name',
                    'users:id,name',
                    'city:id,ville,ville_formatee,code_postal',
                    'departement:id,departement,numero',
                    'structuretype:id,name,slug',
                    'disciplines',
                    'categories',
                    'activites',
                    'activites.discipline',
                    'activites.categorie',
                    'produits',
                    'produits.criteres',
                    'tarifs',
                    'tarifs.tarifType',
                    'tarifs.structureTarifTypeInfos',
                    'plannings',
                ])
                        ->filter(
                            request(['search'])
                        )
                        ->latest()
                        ->paginate(6)
                        ->through(function ($structure) {
                            $disciplinesCount = $structure->disciplines->count();
                            $activitiesCount = $structure->activites->count();
                            $produitsCount = $structure->produits->count();

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
                            'logo' => $structure->logo ? asset($structure->logo) : 'https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
                            'adresses' => $structure->adresses,
                            'categories' => $structure->categories,
                            'disciplines' => $structure->disciplines,
                            'activites' => $structure->activites,
                            'produits' => $structure->produits,
                            'tarifs' => $structure->tarifs,
                            'disciplines_count' => $disciplinesCount,
                            'activites_count' => $activitiesCount,
                            'produits_count' => $produitsCount,
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
        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Create', [
            'structurestypes' => $structurestypes,
            'disciplines' => $disciplines,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
            'email' => ['required', 'max:50', 'email:filter', 'unique:structures,email'],
            'website' => ['nullable', 'url'],
            'phone1' => ['required', 'phone:FR,BE,LU'],
            'phone2' => ['nullable', 'phone:FR,BE,LU'],
            'facebook' => ['nullable', 'url', 'regex:/facebook\.com\/.*/i'],
            'instagram' => ['nullable', 'url', 'regex:/instagram\.com\/.*/i'],
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

        $city = City::where('code_postal', $validated['zip_code'])->firstOrFail();
        $cityId = $city->id;
        $validated['city_id'] = $cityId;

        // check if name and city combined exists in Structure
        $exists = Structure::where('name', $name)
                            ->where('city_id', $validated['city_id'])
                            ->exists();
        if($exists) {
            return Redirect::back()->with('error', 'Ce nom de structure existe déjà dans cette ville.');
        }

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement = Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        $structure = Structure::create($validated);

        $newSlug = $structure->slug . '-' . $structure->id;
        $structure->update(['slug' => $newSlug]);

        if($request->hasFile('logo')) {
            request()->validate(['logo' => ['nullable','image','max:2048']]);
            $path = $request->file('logo')->store('public/structures/' . $structure->id);
            $url = Storage::url($path);
            $structure->update(['logo' => $url]);
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
                    'structure_id' => $structure->id,
                    'attribut_id' => $key,
                    'valeur' => $attribut
                ]);
            }
        }

        Mail::to($structure->email)->send(new StructureCreated($structure));

        return to_route('structures.disciplines.index', $structure->slug)->with('success', 'Votre structure est bien créée, vous allez recevoir une confirmation par email. Vous pouvez, maintenant, ajouter des activités à votre structure.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure)
    {
        $familles = Famille::with([
                    'disciplines' => function ($query) {
                        $query->whereHas('structures');
                    }
                ])
                ->whereHas('disciplines', function ($query) {
                    $query->whereHas('structures');
                })->select(['id', 'name', 'slug'])->get();


        $structure = Structure::with([
            'creator:id,name',
            'users:id,name',
            'adresses'  => function ($query) {
                $query->latest();
            },
            'city:id,ville,ville_formatee,code_postal',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines',
            'disciplines.discipline:id,name,slug',
            'categories',
            'activites',
            'activites.discipline:id,name',
            'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'activites.produits',
            'activites.produits.adresse',
            'activites.produits.criteres',
            'activites.produits.criteres.critere',
            'activites.produits.tarifs',
            'activites.produits.tarifs.tarifType',
            'activites.produits.tarifs.structureTarifTypeInfos',
            'activites.produits.plannings',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo'])
            ->where('slug', $structure->slug)
            ->first();

        $logoUrl = asset($structure->logo);

        $criteres = LienDisciplineCategorieCritere::with(['valeurs' => function ($query) {
            $query->orderBy('defaut', 'desc');
        }])
        ->whereIn('discipline_id', $structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $structure->categories->pluck('categorie_id'))
        ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => $structure,
            'familles' => $familles,
            'criteres' => $criteres,
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

        $allReservationsCount = ProductReservation::count();
        $pendingReservationsCount = ProductReservation::where('pending', true)->count();
        $confirmedReservationsCount = ProductReservation::where('confirmed', true)
            ->count();

        $structurestypes = Structuretype::with(['structuretypeattributs', 'structuretypeattributs.structuretypevaleurs'])->select(['id', 'name', 'slug'])->get();
        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        $structure = Structure::with([
            'adresses'  => function ($query) {
                $query->latest();
            },
            'creator:id,name,email',
            'users:id,name',
            'partenaires',
            'cities:id,ville,ville_formatee',
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines',
            'categories',
            'activites' => function ($query) {
                $query->orderBy('discipline_id');
            },
            'activites.discipline:id,name',
            'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'activites.produits',
            'activites.produits.adresse',
            'activites.produits.criteres',
            'activites.produits.criteres.critere',
            'activites.produits.tarifs',
            'activites.produits.tarifs.tarifType',
            'activites.produits.tarifs.structureTarifTypeInfos',
            'activites.produits.plannings',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'abo_news', 'abo_promo', 'logo'])
            ->where('slug', $structure->slug)
            ->firstOrFail();

        return Inertia::render('Structures/Edit', [
            'structurestypes' => $structurestypes,
            'disciplines' => $disciplines,
            'structure' => $structure,
            'allReservationsCount' => $allReservationsCount,
            'confirmedReservationsCount' => $confirmedReservationsCount,
            'pendingReservationsCount' => $pendingReservationsCount,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        $structure = Structure::where('id', $structure->id)->firstOrFail();

        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
            'email' => ['required', 'max:50', 'email:filter'],
            'website' => ['nullable', 'url'],
            'phone1' => ['required', 'phone:FR,BE,LU'],
            'phone2' => ['nullable', 'phone:FR,BE,LU'],
            'facebook' => ['nullable', 'url', 'regex:/facebook\.com\/.*/i'],
            'instagram' => ['nullable', 'url', 'regex:/instagram\.com\/.*/i'],
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
        ]);

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug . '-' . $structure->id;

        $city = City::where('code_postal', $validated['zip_code'])->firstOrFail();
        $cityId = $city->id;
        $validated['city_id'] = $cityId;

        // check if name and city combined exists in Structure
        $exists = Structure::where('id', '!=', $structure->id)
                            ->where('name', $name)
                            ->where('city_id', $validated['city_id'])
                            ->exists();
        if($exists) {
            return to_route('structures.edit', $structure->slug)->with('error', 'Ce nom de structure existe déjà dans cette ville.');
        }

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement = Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        if($request->hasFile('logo')) {
            request()->validate(['logo' => ['nullable','image','max:2048']]);
            if($structure->logo !== null) {
                Storage::delete('structures/' . $structure->id .'/'. $structure->logo);
            }
            $path = $request->file('logo')->store('public/structures/' . $structure->id);
            $url = Storage::url($path);
            $structure->update(['logo' => $url]);

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

        return to_route('structures.edit', $structure->slug)->with('success', 'Votre structure a été mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure): RedirectResponse
    {
        $structure = Structure::where('id', $structure->id)->first();

        $adresses = StructureAddress::where('structure_id', $structure->id)->get();

        $horaires = StructureHoraire::where('structure_id', $structure->id)->get();

        $users = StructureUser::where('structure_id', $structure->id)->get();

        $typeInfos = StructureTypeInfo::where('structure_id', $structure->id)->get();

        $disciplines = StructureDiscipline::where('structure_id', $structure->id)->get();

        $categories = StructureCategorie::where('structure_id', $structure->id)->get();

        $activites = StructureActivite::where('structure_id', $structure->id)->get();

        $produits = StructureProduit::where('structure_id', $structure->id)->get();

        $criteres = StructureProduitCritere::where('structure_id', $structure->id)->get();

        $plannings = StructurePlanning::where('structure_id', $structure->id)->get();


        if (! Gate::allows('destroy-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de supprimer cette fiche, vous devez être le créateur de la structure ou un administrateur.');
        }


        if($plannings->isNotEmpty()) {
            foreach($plannings as $planning) {
                $planning->delete();
            }
        }

        if($criteres->isNotEmpty()) {
            foreach($criteres as $critere) {
                $critere->delete();
            }
        }

        if($produits->isNotEmpty()) {
            foreach($produits as $produit) {
                $produit->delete();
            }
        }

        if($activites->isNotEmpty()) {
            foreach($activites as $activite) {

                if($activite->image !== null) {
                    Storage::delete($activite->image);
                }

                $activite->delete();
            }
        }

        if($adresses->isNotEmpty()) {
            foreach($adresses as $adresse) {
                $adresse->delete();
            }
        }
        if($typeInfos->isNotEmpty()) {
            foreach($typeInfos as $typeInfo) {
                $typeInfo->delete();
            }
        }
        if($users->isNotEmpty()) {
            foreach($users as $user) {
                $user->delete();
            }
        }

        if($horaires->isNotEmpty()) {
            foreach($horaires as $horaire) {
                $horaire->delete();
            }
        }


        if($categories->isNotEmpty()) {
            foreach($categories as $categorie) {
                $categorie->delete();
            }
        }

        if($disciplines->isNotEmpty()) {
            foreach($disciplines as $discipline) {
                $discipline->delete();
            }
        }


        if($structure->logo) {
            Storage::delete($structure->logo);
        }

        if($structure) {
            $structure->delete();
        }

        return to_route('welcome')->with('success', 'Structure supprimée.');
    }
}
