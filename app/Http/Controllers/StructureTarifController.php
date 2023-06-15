<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use App\Models\StructureTarif;
use Illuminate\Validation\Rule;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructureTarifTypeInfo;
use Illuminate\Support\Facades\Redirect;

class StructureTarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Structure $structure)
    {
        $structure = Structure::with(['produits', 'tarifs', 'tarifs.tarifType', 'tarifs.structureTarifTypeInfos', 'tarifs.structureTarifTypeInfos.tarifTypeAttribut'])
        ->select('id', 'name', 'slug')
        ->where('id', $structure->id)
        ->firstOrFail();


        $tarifTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type', 'slug'])->get();


        $activiteForTarifs = StructureActivite::with(['structure:id,name,slug', 'categorie:id,nom_categorie', 'discipline:id,name', 'produits', 'produits.tarifs', 'produits.tarifs.structureTarifTypeInfos', 'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut'])
        ->where('structure_id', $structure->id)
        ->latest()
        ->get()
        ->groupBy('discipline.id')
        ->map(function ($disciplineActivites, $disciplineId) {
            return [
                'id' => $disciplineId,
                'disciplineName' => $disciplineActivites->first()->discipline->name,
                'categories' => $disciplineActivites->groupBy('categorie.id')->map(function ($categorieItems, $categoryId) {
                    $activites = $categorieItems->map(function ($activiteItem) {
                        return [
                            'id' => $activiteItem->id,
                            'titre' => $activiteItem->titre,
                            'disciplineId' => $activiteItem->discipline_id,
                            'categorieId' => $activiteItem->categorie_id,
                            'produits' => $activiteItem->produits->map(function ($produitItem) {
                                return [
                                    'id' => $produitItem->id,
                                    'disciplineId' => $produitItem->discipline_id,
                                    'categorieId' => $produitItem->categorie_id,
                                    'activiteId' => $produitItem->activite_id,
                                    'tarifs' => $produitItem->tarifs->map(function ($tarifItem) {
                                        return [
                                            'id' => $tarifItem->id,
                                            'typeId' => $tarifItem->type_id,
                                            'titre' => $tarifItem->titre,
                                            'description' => $tarifItem->description,
                                            'amount' => $tarifItem->amount,
                                            'produits' => $tarifItem->produits,
                                            'infos' => $tarifItem->structureTarifTypeInfos->map(function ($infoItem) {
                                                return [
                                                    'id' => $infoItem->id,
                                                    'attribut_id' => $infoItem->attribut_id,
                                                    'valeur' => $infoItem->valeur,
                                                    'unite'=> $infoItem->unite,
                                                    'tarifTypeAttribut' => $infoItem->tarifTypeAttribut
                                                ];
                                            }),
                                        ];
                                    }),
                                ];
                            }),
                        ];
                    });
                    return [
                        'id' => $categoryId,
                        'disciplineId' => $categorieItems->first()->discipline->id,
                        'name' => $categorieItems->first()->categorie->nom_categorie ?? 'Sans Catégorie',
                        'activites' => $activites,
                    ];
                }),
            ];
        });


        return Inertia::render('Tarifs/Index', [
            'structure' => $structure,
            'tarifTypes' => $tarifTypes,
            'activiteForTarifs' => $activiteForTarifs,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Structure $structure)
    {
        $request->validate([
            'structure_id' => ['nullable', Rule::exists('structures', 'id')],
            'titre' => ['nullable'],
            'description' => ['nullable'],
            'tarifType' => ['nullable', Rule::exists('liste_tarifs_types', 'id')],
            'attributs' => ['nullable'],
            'amount' => ['required', 'numeric'],
            'disciplines' => ['nullable'],
            'categories' => ['nullable'],
            'activites' => ['nullable'],
            'produits' => ['required'],
            'uniteDuree' => ['nullable'],
        ]);

        $structure = Structure::with(['disciplines', 'categories', 'activites', 'produits'])->where('id', $structure->id)->firstOrFail();

        $structureTarif = StructureTarif::create([
            'structure_id' => $structure->id,
            'type_id' => $request->tarifType,
            'titre' => $request->titre ?? "",
            'description' => $request->description ?? "",
            'amount' => $request->amount,
        ]);

        foreach($request->produits as $key => $value) {
            $structureProduit = StructureProduit::where('id', $key)->first();
            if($value === true) {
                $structureTarif->produits()->attach($structureProduit->id);
            }
        }

        $tarifType = ListeTarifType::with('tariftypeattributs')->where('id', $structureTarif->type_id)->first();

        foreach($request->attributs as $key => $valeur) {
            foreach($tarifType->tariftypeattributs as $tariftypeattribut) {
                if($tariftypeattribut->id === $key) {
                    $tarifAttribut = $structureTarif->structureTarifTypeInfos()->create([
                        'structure_id' => $structure->id,
                        'tarif_id' => $structureTarif->id,
                        'type_id' => $tarifType->id,
                        'attribut_id' => $key,
                        'valeur' => $valeur,
                    ]);
                    if($tariftypeattribut->attribut === 'Duree') {
                        $tarifAttribut->update(['unite'=> $request->uniteDuree['name']]);
                    }
                }
            }
        }

        return Redirect::back()->with('success', "Le tarif a bien été enregistré pour vos produits");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure, $tarif)
    {
        $request->validate([
                    'structure_id' => ['nullable', Rule::exists('structures', 'id')],
                    'titre' => ['nullable'],
                    'description' => ['nullable'],
                    'tarifType' => ['nullable', Rule::exists('liste_tarifs_types', 'id')],
                    'attributs' => ['nullable'],
                    'amount' => ['required', 'numeric'],
                    'disciplines' => ['nullable'],
                    'categories' => ['nullable'],
                    'activites' => ['nullable'],
                    'produits' => ['required'],
                    'uniteDuree' => ['nullable'],
                ]);

        $structure = Structure::with(['disciplines', 'categories', 'activites', 'produits'])->where('id', $structure->id)->firstOrFail();

        $tarif = StructureTarif::with('structureTarifTypeInfos')->where('id', $tarif)->firstOrFail();

        $structureTarif = $tarif->updateOrCreate(
            ['structure_id' => $structure->id,],
            ['type_id' => $request->tarifType,
            'titre' => $request->titre ?? "",
            'description' => $request->description ?? "",
            'amount' => $request->amount]
        );

        $structureTarif->produits()->detach();

        foreach($request->produits as $key => $value) {
            $structureProduit = StructureProduit::where('id', $key)->first();
            if($value === true) {
                $structureTarif->produits()->attach($structureProduit->id);
            }
        }

        $tarifType = ListeTarifType::with('tariftypeattributs')->where('id', $structureTarif->type_id)->first();

        foreach($structureTarif->structureTarifTypeInfos as $info) {
            $info->delete();
        }

        foreach($request->attributs as $key => $valeur) {
            foreach($tarifType->tariftypeattributs as $tariftypeattribut) {
                if($tariftypeattribut->id === $key) {
                    $tarifAttribut = $structureTarif->structureTarifTypeInfos()->create(
                        [ 'structure_id' => $structure->id,
                        'tarif_id' => $structureTarif->id,
                        'type_id' => $structureTarif->type_id,
                        'attribut_id' => $key,
                        'valeur' => $valeur ]
                    );
                    if($tariftypeattribut->attribut === 'Duree') {
                        $tarifAttribut->update(['unite'=> $request->uniteDuree['name']]);
                    }
                }
            }
        }

        return Redirect::back()->with('success', "Le tarif a bien été mis à jour pour vos produits");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, $tarif, $produit)
    {
        //On ne fait que délier le tarif au produit, sans supprimer totalement le tarif
        $produit = StructureProduit::where('id', $produit)->firstOrFail();

        $tarif = StructureTarif::with('structureTarifTypeInfos')->where('id', $tarif)->where('structure_id', $structure->id)->firstOrFail();

        $tarif->produits()->detach($produit->id);

        //Si on veut supprimer entierement le tarif:
        // $infos = StructureTarifTypeInfo::where('tarif_id', $tarif->id)->get();
        // if($infos->isNotEmpty()) {
        //     foreach($infos as $info) {
        //         $info->delete();
        //     }
        // }
        // $tarif->produits()->detach();
        // $tarif->delete();

        return Redirect::back()->with('success', 'Le tarif pour ce produit a bien été supprimé');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyTarif(Structure $structure, $tarif)
    {
        $tarif = StructureTarif::with('structureTarifTypeInfos')->where('id', $tarif)->where('structure_id', $structure->id)->firstOrFail();

        // Si on veut supprimer entierement le tarif:
        $infos = StructureTarifTypeInfo::where('tarif_id', $tarif->id)->get();
        if($infos->isNotEmpty()) {
            foreach($infos as $info) {
                $info->delete();
            }
        }
        $tarif->produits()->detach();
        $tarif->delete();

        return Redirect::back()->with('success', 'Le tarif a bien été supprimé');
    }

    public function duplicate(Structure $structure, $tarif, $produit)
    {
        $produit = StructureProduit::where('id', $produit)->firstOrFail();

        $originalTarif = StructureTarif::with('structureTarifTypeInfos')->where('id', $tarif)->where('structure_id', $structure->id)->firstOrFail();

        $originalInfos = StructureTarifTypeInfo::where('tarif_id', $originalTarif->id)->get();

        $newTarif = new StructureTarif();
        $newTarif->structure_id = $originalTarif->structure_id;
        $newTarif->type_id = $originalTarif->type_id;
        $newTarif->titre = $originalTarif->titre;
        $newTarif->description = $originalTarif->description;
        $newTarif->amount = $originalTarif->amount;
        $newTarif->id = null;
        $newTarif->save();

        $newTarif->produits()->attach($produit->id);

        foreach($originalInfos as $tarifInfo) {
            $newTarifInfo = new StructureTarifTypeInfo();
            $newTarifInfo->structure_id = $tarifInfo->structure_id;
            $newTarifInfo->tarif_id = $newTarif->id;
            $newTarifInfo->type_id = $tarifInfo->type_id;
            $newTarifInfo->attribut_id = $tarifInfo->attribut_id;
            $newTarifInfo->valeur = $tarifInfo->valeur;
            $newTarifInfo->id = null;
            $newTarifInfo->save();
        }

        return Redirect::back()->with('success', "Le tarif a bien été dupliqué");
    }
}
