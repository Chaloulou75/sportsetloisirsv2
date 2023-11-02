<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\City;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Categorie;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\ListeTarifType;
use Illuminate\Validation\Rule;
use App\Models\StructureAddress;
use App\Models\StructureHoraire;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
use App\Models\ProductReservation;
use App\Models\StructureCategorie;
use App\Models\StructureDiscipline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\StructureProduitCritere;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\StructureProduitSousCritere;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\LienDisciplineCategorieCritereValeur;
use App\Models\LiensDisCatCritValSsCrit;

class ActiviteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $structure)
    {
        $validated = request()->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
        ]);

        $structure = Structure::findOrFail($validated['structure_id']);
        $discipline = ListDiscipline::findOrFail($validated['discipline_id']);

        $structureAdresse = StructureAddress::where('structure_id', $structure->id)->firstOrfail();

        // check if structure_id and discipline_id combined exists in StructureDiscipline
        $exists = StructureDiscipline::where('structure_id', $structure->id)
                                    ->where('discipline_id', $discipline->id)
                                    ->exists();
        if($exists) {
            return to_route('structures.disciplines.index', $structure)->with('error', 'Cette discipline est déjà associée à cette structure.');
        }

        $structureDiscipline = $structure->disciplines()->create([
            'discipline_id' => $discipline->id,
            'nb_produits' => 1,
        ]);

        // structureCategorie
        $validatedData = request()->validate([
            'categories_id' => ['required', 'array', Rule::exists('categories', 'id')]
        ]);

        foreach ($validatedData['categories_id'] as $category_id) {

            $disCat = LienDisciplineCategorie::where('discipline_id', $discipline->id)->where('categorie_id', $category_id)->firstOrfail();

            $structureActiviteCategorie = $structure->categories()->create([
                'discipline_id' => $discipline->id,
                'categorie_id' => $disCat->id,
            ]);

            $titre = $disCat->nom_categorie_pro . ' de ' . $discipline->name ;

            $activite = $structure->activites()->create([
                'discipline_id' => $discipline->id,
                'categorie_id' => $disCat->id,
                'titre' => $titre,
                'description' => "",
                'image' => "",
                "actif" => 1,
            ]);

            $produit = $structure->produits()->create([
                'discipline_id' => $discipline->id,
                'categorie_id' => $disCat->id,
                'activite_id' => $activite->id,
                "actif" => 1,
                'lieu_id' => $structureAdresse->id,
                'reservable' => 0,
            ]);

            $criteres = LienDisciplineCategorieCritere::where('discipline_id', $discipline->id)->where('categorie_id', $disCat->id)->get();

            foreach($criteres as $critere) {
                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $critere->id)->first();
                if ($defaut) {
                    $this->createStructureProduitCritere($structure, $activite, $produit, $critere->id, $defaut->id, $defaut->valeur);
                } else {
                    $this->createStructureProduitCritere($structure, $activite, $produit, $critere->id, null, null);
                }
            }
        }

        return to_route('structures.disciplines.index', $structure)->with('success', 'Activité créée, vous pouvez ajouter d\'autres activités à votre structure.');
    }

    public function show($activite)
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $activite = StructureActivite::with([
            'structure:id,name,slug,presentation_courte,presentation_longue,address,zip_code,city,country,address_lat,address_lng,user_id,structuretype_id,website,email,facebook,instagram,youtube,tiktok,phone1,phone2,date_creation,view_count,departement_id,logo',
            'structure.creator:id,name',
            'structure.users:id,name',
            'structure.adresses'  => function ($query) {
                $query->latest();
            },
            'structure.city:id,ville,ville_formatee,code_postal',
            'structure.departement:id,departement,numero',
            'structure.structuretype:id,name,slug',
            'dates',
            'instructeurs',
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.criteres.critere_valeur.sous_criteres.prodSousCritValeurs',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
            'produits.plannings',
        ])->where('id', $activite)->first();

        $logoUrl = asset($activite->structure->logo);

        $criteres = LienDisciplineCategorieCritere::with([
                'valeurs' => function ($query) {
                    $query->orderBy('defaut', 'desc');
                },
                'valeurs.sous_criteres',
                'valeurs.sous_criteres.sous_criteres_valeurs'
            ])
                ->whereIn('discipline_id', $activite->structure->disciplines->pluck('discipline_id'))->whereIn('categorie_id', $activite->structure->categories->pluck('categorie_id'))
                ->get();

        $activiteSimilaires = StructureActivite::with([
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits',
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.criteres.critere_valeur.sous_criteres.prodSousCritValeurs',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.plannings'
            ])->whereNot('id', $activite->id)
            ->where('discipline_id', $activite->discipline_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Structures/Activites/Show', [
                    'familles' => $familles,
                    'listDisciplines' => $listDisciplines,
                    'allCities' => $allCities,
                    'logoUrl' => $logoUrl,
                    'activite' => $activite,
                    'criteres' => $criteres,
                    'activiteSimilaires' => $activiteSimilaires
        ]);
    }

    /**
     * Show the form for editing a resource.
     */
    public function edit(Structure $structure, $activite) {}

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Structure $structure, $activite)
    {
        if (!Gate::allows('update-structure', $structure)) {
            return to_route('structures.show', $structure->slug)->with('error', 'Vous n\'avez pas la permission de modifier cette activité, vous devez être le créateur de l\'activité ou un administrateur.');
        }

        $request->validate([
                'titre' => 'required|string|min:3',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                // 'actif' => 'nullable|boolean',
            ]);

        $structureActivite = StructureActivite::with(['structure','categorie', 'discipline'])->where('structure_id', $structure->id)
                    ->where('id', $activite)
                    ->first();


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/structures/' . $structure->id . '/activites/' . $structureActivite->id);

            $url = Storage::url($path);
            $structureActivite->update(['image' => $url]);
        }

        $structureActivite->update([
            'titre' => $request->titre,
            'description' => $request->description,
            // 'actif' => 1,
        ]);

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $structureActivite->discipline->slug, 'categorie' => $structureActivite->categorie->id, ])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $activite): RedirectResponse
    {
        $activite = StructureActivite::where('id', $activite)->first();

        $produits = StructureProduit::where('activite_id', $activite->id)->get();

        $criteres = StructureProduitCritere::where('activite_id', $activite->id)->get();

        $plannings = StructurePlanning::where('activite_id', $activite->id)->get();

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

        if($activite->image !== null) {
            Storage::delete($activite->image);
        }

        if($activite) {
            $activite->delete();
        }

        return to_route('structures.disciplines.index', ['structure' => $structure->slug ])->with('success', 'l\'activité a été supprimée.');

    }

    public function toggleactif(Request $request, Structure $structure, $activite): RedirectResponse
    {
        $request->validate([
            'actif' => 'required|boolean',
        ]);

        $structureActivite = StructureActivite::with([
            'structure',
            'categorie',
            'discipline'
        ])->where('structure_id', $structure->id)
            ->where('id', $activite)
            ->first();

        $structureActivite->update(['actif' => $request->actif]);

        return to_route('structures.categories.show', ['structure' => $structure->slug, 'discipline' => $structureActivite->discipline->slug, 'categorie' => $structureActivite->categorie->id])->with('success', 'Activité mise à jour, ajoutez d\'autres activités à votre structure.');
    }

    public function newactivitystore(Request $request, Structure $structure, $discipline): RedirectResponse
    {
        $request->validate([
            'structure_id' => ['required', Rule::exists('structures', 'id')],
            'discipline_id' => ['required', Rule::exists('liste_disciplines', 'id')],
            'categorie_id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'titre' => 'nullable|string|min:3',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'actif' => 'required|boolean',
            'criteres' => ['nullable'],
            'souscriteres' => ['nullable'],
            'adresse' => ['nullable', Rule::exists('structure_adresse', 'id')],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'date' => ['nullable'],
            'time' => ['nullable'],
            'date_debut' => ['nullable'],
            'time_debut' => ['nullable'],
            'months' => ['nullable'],
            'instructeur_email' => ['nullable', 'email:filter', 'exists:users,email'],
            'instructeur_contact' => ['nullable'],
            'instructeur_phone' => ['nullable'],
            'rayon_km' => ['nullable'],
        ]);

        // dd($request->criteres, $request->souscriteres);

        $structure = Structure::with('adresses')->findOrFail($structure->id);

        $categorieDiscName = LienDisciplineCategorie::with('discipline')->where('id', $request->categorie_id)->first();

        if($request->address) {
            //check if address exist
            foreach($structure->adresses as $address) {
                if (($address->address_lat === $request->address_lat) && ($address->address_lng === $request->address_lng)) {
                    return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline])->with('error', 'Cette adresse existe déjà dans votre liste d\'adresses');
                }
            }
            $city = City::where('code_postal', $request->zip_code)->firstOrFail();
            $cityId = $city->id;

            $validatedAddress = [
                'structure_id' => $structure->id,
                'name' => $structure->name,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'city' => $request->city,
                'country' => $request->country,
                'city_id' => $cityId,
                'country_id' => $structure->country_id,
                'address_lat' => $request->address_lat,
                'address_lng' => $request->address_lng,
                'phone' => $structure->phone1,
                'email' => $structure->email,
            ];

            $newStructureAddress = StructureAddress::create($validatedAddress);
            $structureAddressId = $newStructureAddress->id;

        } elseif ($request->adresse) {
            $structureAddressId = $request->adresse;
        } else {
            $structureAddressId = $structure->adresses->first()->id;
        }

        // Dates et horaires
        if($request->date_debut) {
            $date_debut = Carbon::parse($request->date_debut)->format('Y-m-d');
        }

        if($request->time_debut) {
            $hour_debut = $request->time_debut['hours'];
            $minute_debut = $request->time_debut['minutes'];
            $time_debut = sprintf('%02d:%02d', $hour_debut, $minute_debut);
        }

        if (is_array($request->months) && count($request->months) === 2) {
            $startMonth = Carbon::create($request->months[0]['year'], $request->months[0]['month'] + 1, 1)->startOfMonth();
            $endMonth = Carbon::create($request->months[1]['year'], $request->months[1]['month'] + 1, 1)->endOfMonth();
        }
        if($request->date || $request->time) {

            $dayopen = Carbon::parse($request->date[0])->format('Y-m-d');
            $dayclose = Carbon::parse($request->date[1])->format('Y-m-d');

            $heureopen = $request->time[0]['hours'];
            $minuteopen = $request->time[0]['minutes'];
            // Construct the time string in HH:ii:ss format
            $houropen = sprintf('%02d:%02d', $heureopen, $minuteopen);

            $heureclose = $request->time[1]['hours'];
            $minuteclose = $request->time[1]['minutes'];
            // Construct the time string in HH:ii:ss format
            $hourclose = sprintf('%02d:%02d', $heureclose, $minuteclose);

            $dayTime = StructureHoraire::firstOrCreate([
                'structure_id' => $structure->id,
                'dayopen' => $dayopen ?? null,
                'dayclose' => $dayclose ?? null,
                'houropen' => $houropen ?? null,
                'hourclose' => $hourclose ?? null,
            ]);
        }

        $structureActivite = $structure->activites()->create([
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'titre' => $request->titre ?? $categorieDiscName->nom_categorie_pro . ' de ' . $categorieDiscName->discipline->name,
            'description' => $request->description,
            'image' => "",
            "actif" => true,
        ]);


        if (
            (isset($dayopen) && !empty($dayopen)) ||
            (isset($dayclose) && !empty($dayclose)) ||
            (isset($houropen) && !empty($houropen)) ||
            (isset($hourclose) && !empty($hourclose)) ||
            (isset($date_debut) && !empty($date_debut)) ||
            (isset($time_debut) && !empty($time_debut)) ||
            (isset($startMonth) && !empty($startMonth)) ||
            (isset($endMonth) && !empty($endMonth))
        ) {
            $data = [
                'structure_activite_id' => $structureActivite->id,
                'dayopen' => $dayopen ?? null,
                'dayclose' => $dayclose ?? null,
                'houropen' => $houropen ?? null,
                'hourclose' => $hourclose ?? null,
                'date_debut' => $date_debut ?? null,
                'time_debut' => $time_debut ?? null,
                'start_month' => $startMonth ?? null,
                'end_month' => $endMonth ?? null,
            ];

            $structureActivite->dates()->create($data);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/structures/' . $structure->id . '/activites/' . $structureActivite->id);
            $url = Storage::url($path);
            $structureActivite->update(['image' => $url]);
        }

        $structureProduit = $structure->produits()->create([
            'discipline_id' => $request->discipline_id,
            'categorie_id' => $request->categorie_id,
            'activite_id' => $structureActivite->id,
            "actif" => true,
            'lieu_id' => $structureAddressId,
            'horaire_id' => $dayTime->id ?? null,
            'reservable' => 0,
        ]);

        // $criteres = LienDisciplineCategorieCritere::with('valeurs')
        // ->where('discipline_id', $request->discipline_id)
        // ->where('categorie_id', $request->categorie_id)
        // ->get();
        // $critereIds = $criteres->pluck('id');

        $criteresValuesSets = $request->criteres;

        if (isset($criteresValuesSets)) {
            foreach ($criteresValuesSets as $critereId => $criteresValues) {
                $defaut = LienDisciplineCategorieCritereValeur::where('defaut', 1)->where('discipline_categorie_critere_id', $critereId)->first();

                $this->insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critereId, $criteresValues, $defaut);
            }
        }

        $souscriteresValues = $request->souscriteres;
        if(isset($souscriteresValues)) {
            foreach ($souscriteresValues as $souscritereId => $souscritereValue) {

                $sousCritere = LiensDisCatCritValSsCrit::with([
                    'critere_valeur',
                    'sous_criteres_valeurs'
                ])->find($souscritereId);

                $critereId = $sousCritere->critere_valeur->discipline_categorie_critere_id;
                $critereValeurId = $sousCritere->critere_valeur->id;
                $sousCritereId = $sousCritere->id;

                $this->insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
            }
        }

        if($request->instructeur_email) {
            $user = User::where('email', $request->instructeur_email)->firstOrFail();
            $instructeur = $structureActivite->instructeurs()->attach($user, [
                'contact' => $request->instructeur_contact,
                'email' => $request->instructeur_email,
                'phone' => $request->instructeur_phone,
            ]);
        }

        return to_route('structures.disciplines.show', ['structure' => $structure->slug, 'discipline' => $discipline])->with('success', 'Activité ajoutée avec succès, ajoutez d\'autres activités à votre structure.');
    }

    private function insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critereId, $criteresValues, $defaut)
    {
        if (isset($criteresValues['valeur'])) {
            $critereValueId = $criteresValues['id'];
            $critereValue = $criteresValues['valeur'];
            $valeurId = isset($critereValueId) ? $critereValueId : ($defaut ? $defaut->id : null);
            $valeur = isset($critereValue) ? $critereValue : ($defaut ? $defaut->valeur : null);

            $this->createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, $valeurId, $valeur);
        } else {
            foreach ($criteresValues as $critereValue) {
                $this->insertCriteresRecursively($structure, $structureActivite, $structureProduit, $critereId, $critereValue, $defaut);
            }
        }
    }

    private function createStructureProduitCritere($structure, $structureActivite, $structureProduit, $critereId, $valeurId = null, $valeur = null)
    {
        StructureProduitCritere::create([
            'structure_id' => $structure->id,
            'discipline_id' => $structureActivite->discipline_id,
            'categorie_id' => $structureActivite->categorie_id,
            'activite_id' => $structureActivite->id,
            'produit_id' => $structureProduit->id,
            'critere_id' => $critereId,
            'valeur_id' => $valeurId,
            'valeur' => $valeur ?? "",
        ]);
    }

    private function insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue)
    {
        if (isset($souscritereValue['id'])) {
            $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
        } elseif(is_array($souscritereValue)) {
            foreach ($souscritereValue as $subSouscriteresValue) {
                $this->insertSousCriteresRecursively($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $subSouscriteresValue);
            }
        } else {
            $this->createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscritereValue);
        }
    }

    private function createProduitSousCritere($structureActivite, $structureProduit, $critereId, $critereValeurId, $sousCritereId, $souscriteresValues)
    {
        StructureProduitSousCritere::create([
            'activite_id' => $structureActivite->id,
            'produit_id' => $structureProduit->id,
            'critere_id' => $critereId,
            'critere_valeur_id' => $critereValeurId,
            'sous_critere_id' => $sousCritereId,
            'sous_critere_valeur_id' => $souscriteresValues['id'] ?? null,
            'valeur' => $souscriteresValues['valeur'] ?? $souscriteresValues,
        ]);
    }

}
