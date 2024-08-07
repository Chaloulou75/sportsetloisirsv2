<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\StructureUser;
use App\Mail\StructureCreated;
use App\Models\ListDiscipline;
use App\Models\StructureTarif;
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
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\StructureTarifTypeInfo;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\LienDisciplineCategorieCritereResource;
use App\Http\Resources\ListDisciplineResource;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\StructureResource;
use App\Http\Resources\StructuretypeResource;
use App\Models\LienDisciplineCategorieCritere;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });
        $structures = Structure::with([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'departement',
                'structuretype',
                'disciplines',
                'categories',
                'activites',
            ])->withCount(['disciplines', 'activites'])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'logo'])
                        ->filter(
                            request(['search'])
                        )
                        ->latest()
                        ->paginate(12)
                        ->withQueryString();

        return Inertia::render('Structures/Index', [
            'structures' => fn () => StructureResource::collection($structures),
            'filters' => request()->all(['search']),
            'familles' => fn () => FamilleResource::collection($familles),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $structurestypes = Structuretype::with(['attributs', 'attributs.valeurs'])->select(['id', 'name', 'slug'])->get();

        $disciplines = ListDiscipline::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structures/Create', [
            'structurestypes' => fn () => StructuretypeResource::collection($structurestypes),
            'disciplines' => fn () => ListDisciplineResource::collection($disciplines),
            'familles' => fn () => FamilleResource::collection($familles),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
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
            $path = $request->file('logo')->store('structures/' . $structure->id . '/logo', 'public');
            $structure->update(['logo' => $path]);
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
        if($attributs) {
            foreach ($attributs as $key => $attribut) {
                if (isset($attribut)) {

                    foreach($structure->structuretype->attributs as $typeAttribut) {
                        if($key === $typeAttribut->id) {

                            if(is_array($attribut) && isset($attribut['id'])) {

                                $structure->structuretype_infos()->create([
                                    'attribut_id' => $key,
                                    'valeur_id' => $attribut['id'],
                                    'valeur' => $attribut['nom'],

                                ]);

                            } else {
                                $structure->structuretype_infos()->create([
                                    'attribut_id' => $key,
                                    'valeur_id' => null,
                                    'valeur' => $attribut,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        Mail::to($structure->email)->send(new StructureCreated($structure));

        return to_route('structures.disciplines.index', $structure->slug)->with('success', 'Votre structure est bien créée, vous allez recevoir une confirmation par email. Vous pouvez, maintenant, ajouter des activités à votre structure.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Structure $structure): Response
    {
        $filters = $request->only(['crit', 'ssCrit']);
        $page = $request->input('page', 1);

        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('all_cities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('list_disciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $structure = Structure::with([
            'creator:id,name',
            'users:id,name',
            'adresses'  => function ($query) {
                $query->latest();
            },
            'departement',
            'structuretype',
            'disciplines',
            'disciplines.str_categories' => function ($query) {
                $query->withCount('str_activites');
            },
            'disciplines.str_categories.str_activites' => function ($query) use ($filters) {
                $query->whereHas('produits', function ($subQuery) use ($filters) {
                    $subQuery->filter($filters);
                });
            },
            'disciplines.str_categories.str_activites.discipline',
            'disciplines.str_categories.str_activites.produits' => function ($query) use ($filters) {
                $query->filter($filters);
            },
            'disciplines.str_categories.str_activites.produits.adresse',
            'disciplines.str_categories.str_activites.produits.criteres',
            'disciplines.str_categories.str_activites.produits.criteres.critere',
            'disciplines.str_categories.str_activites.produits.criteres.critere_valeur',
            'disciplines.str_categories.str_activites.produits.criteres.critere_valeur.sous_criteres',
            'disciplines.str_categories.str_activites.produits.criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs.sous_critere_valeur',
            'disciplines.str_categories.str_activites.produits.criteres.sous_criteres',
            'disciplines.str_categories.str_activites.produits.criteres.sous_criteres.sous_critere',
            'disciplines.str_categories.str_activites.produits.criteres.sous_criteres.sous_critere_valeur',
            'disciplines.str_categories.str_activites.produits.plannings',
        ])->withCount([
            'disciplines',
        ])->find($structure->id);

        $allStructureTypes = Structuretype::whereHas('structures')->select(['id', 'name', 'slug'])->get();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                ->whereIn('discipline_id', $structure->activites->pluck('discipline_id'))
                ->whereIn('categorie_id', $structure->activites->pluck('categorie_id'))
                ->where('visible_front', true)
                ->get();

        $currentRoute = [
            'name' => 'structures.show',
            'params' => [
                'structure' => $structure,
            ]
        ];

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => fn () => StructureResource::make($structure),
            'familles' => fn () => FamilleResource::collection($familles),
            'allCities' => fn () => CityResource::collection($allCities),
            'listDisciplines' => fn () => ListDisciplineResource::collection($listDisciplines),
            'criteres' => fn () => LienDisciplineCategorieCritereResource::collection($criteres),
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'allStructureTypes' => fn () => StructuretypeResource::collection($allStructureTypes),
            'filters' => $filters ?? null,
            'currentRoute' => $currentRoute,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure): Response
    {
        if (!Gate::allows('update-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission d\'éditer cette fiche, vous devez être le créateur de la structure ou un administrateur.');
        }

        $allReservationsCount = ProductReservation::where('structure_id', $structure->id)->count();
        $pendingReservationsCount = ProductReservation::where('structure_id', $structure->id)->where('pending', true)->count();
        $confirmedReservationsCount = ProductReservation::where('structure_id', $structure->id)->where('confirmed', true)
            ->count();

        $structurestypes = Structuretype::with(['attributs', 'attributs.valeurs'])->select(['id', 'name', 'slug'])->get();

        $structure->load([
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'creator',
                'users',
                'departement',
                'structuretype',
                'structuretype_infos',
            ]);

        return Inertia::render('Structures/Edit', [
            'structurestypes' => fn () => StructuretypeResource::collection($structurestypes),
            'structure' => fn () => StructureResource::make($structure),
            'allReservationsCount' => fn () => $allReservationsCount,
            'confirmedReservationsCount' => fn () => $confirmedReservationsCount,
            'pendingReservationsCount' => fn () => $pendingReservationsCount,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure): RedirectResponse
    {
        // $structure = Structure::where('id', $structure->id)->firstOrFail();
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
            return to_route('structures.edit', $structure)->with('error', 'Ce nom de structure existe déjà dans cette ville.');
        }

        $departmentNumber = substr($validated['zip_code'], 0, 2);
        $departement = Departement::where('numero', $departmentNumber)->firstOrFail();
        $validated['departement_id'] = $departement->id;

        if($request->hasFile('logo')) {
            request()->validate(['logo' => ['nullable','image','max:2048']]);
            if($structure->logo !== null) {
                Storage::delete('structures/' . $structure->id . '/' . $structure->logo);
            }
            $path = $request->file('logo')->store('structures/' . $structure->id, 'public');
            $structure->update(['logo' => $path]);
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
        if($attributs) {
            $structure->structuretype_infos()->delete();
            foreach ($attributs as $key => $attribut) {
                if (isset($attribut)) {
                    foreach($structure->structuretype->attributs as $typeAttribut) {
                        if($key === $typeAttribut->id) {
                            if(is_array($attribut) && isset($attribut['id'])) {
                                $structure->structuretype_infos()->create(
                                    [
                                        'attribut_id' => $key,
                                        'valeur_id' => $attribut['id'],
                                        'valeur' => $attribut['nom'],
                                    ]
                                );
                            } else {
                                $structure->structuretype_infos()->create(
                                    [
                                        'attribut_id' => $key,
                                        'valeur_id' => null,
                                        'valeur' => $attribut,
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }

        return to_route('structures.edit', $structure)->with('success', 'Votre structure a été mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure): RedirectResponse
    {
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

        $tarifs = StructureTarif::with('structureTarifTypeInfos')->where('structure_id', $structure->id)->get();

        if (!Gate::allows('destroy-structure', $structure)) {
            return Redirect::route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de supprimer cette fiche, vous devez être le créateur de la structure ou un administrateur.');
        }

        if($tarifs->isNotEmpty()) {
            foreach($tarifs as $tarif) {
                $infos = StructureTarifTypeInfo::where('tarif_id', $tarif->id)->get();
                if($infos->isNotEmpty()) {
                    foreach($infos as $info) {
                        $info->delete();
                    }
                }
                $tarif->produits()->detach();
                $tarif->delete();
            }
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
            Storage::disk('public')->delete($structure->logo);
        }

        if($structure) {
            $structure->delete();
        }

        return to_route('welcome')->with('success', 'Structure supprimée.');
    }
}
