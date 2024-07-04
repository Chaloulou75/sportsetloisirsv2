<?php

namespace App\Http\Controllers;

use App\Http\Resources\CritereResource;
use App\Http\Resources\LienDisciplineCategorieResource;
use App\Http\Resources\ListDisciplineResource;
use App\Http\Resources\TypeChampResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Critere;
use App\Models\Categorie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;
use App\Models\TypeChamp;

class CategoryDisciplineCritereController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'critere.id' => ['required', Rule::exists('liste_criteres', 'id')],
            'categorie.id' => ['required', Rule::exists('liens_disciplines_categories', 'id')],
            'type_champ.id' => ['required', Rule::exists('type_champs', 'id')],
            'type_champ.type' => ['required', 'string', 'max:255'],
            'nom' => ['nullable', 'string'],
        ]);

        $discCat = LienDisciplineCategorie::with(['discipline:id,name,slug'])->findOrFail($request->categorie['id']);

        LienDisciplineCategorieCritere::create([
            "discipline_id" => $discCat->discipline_id,
            "categorie_id" => $request->categorie['id'],
            "critere_id" => $request->critere['id'],
            "nom" => $request->nom ?? $request->critere['nom'],
            "type_champ_form" => $request->type_champ['type'],
            "type_champ_id" => $request->type_champ['id'],
            "visible_back" => true,
            "visible_front" => true,
            "visible_block" => true,
            "indexable" => false,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCat->discipline->slug, 'categorie' => $discCat])->with('success', 'Critère ajouté avec succès');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline, LienDisciplineCategorie $categorie): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        $categorie = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'criteres' => function ($query) {
                $query->orderBy('ordre');
            },
            'criteres.type_champ',
            'criteres.critere',
            'criteres.valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
            'criteres.valeurs.sous_criteres',
            'criteres.valeurs.sous_criteres.type_champ',
            'criteres.valeurs.sous_criteres.sous_criteres_valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
        ])
        ->findOrFail($categorie->id);

        $categories = LienDisciplineCategorie::with([
                'discipline',
                'categorie',
                'criteres',
                'criteres.type_champ',
                'criteres.critere',
                'criteres.valeurs',
                'criteres.valeurs.sous_criteres',
                'criteres.valeurs.sous_criteres.type_champ',
                'criteres.valeurs.sous_criteres.sous_criteres_valeurs'
            ])
            ->where('discipline_id', $discipline->id)
            ->get();

        $listeCriteres = Critere::select(['id', 'nom'])->get();
        $typeChamps = TypeChamp::all();

        return Inertia::render('Admin/Disciplines/Categories/Criteres/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categorie' => fn () => LienDisciplineCategorieResource::make($categorie),
            'categories' => fn () => LienDisciplineCategorieResource::collection($categories),
            'discipline' => fn () => ListDisciplineResource::make($discipline),
            'listeCriteres' => fn () => CritereResource::collection($listeCriteres),
            'type_champs' => fn () => TypeChampResource::collection($typeChamps),
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisciplineCategorieCritere $critere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'visible_front' => 'required|boolean:0,1,true,false',
            'visible_back' => 'required|boolean:0,1,true,false',
            'visible_block' => 'required|boolean:0,1,true,false',
            'ordre' => 'nullable|numeric',
            'indexable' => 'nullable|boolean:0,1,true,false',
            'unite' => 'nullable|string|max:255',
        ]);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie', 'valeurs'])->findOrFail($critere->id);

        $discCatCritere->update([
            'visible_front' => $request->visible_front,
            'visible_back' => $request->visible_back,
            'visible_block' => $request->visible_block,
            'ordre' => $request->ordre,
            'indexable' => $request->indexable,
            'unite' => $request->unite,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Visibilité du critère mise à jour');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatename(Request $request, LienDisciplineCategorieCritere $critere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);
        $request->validate([
            'nom' => 'required|string',
        ]);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie', 'valeurs'])->findOrFail($critere->id);

        $discCatCritere->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Nom du critère mis à jour');
    }

    public function unite(Request $request, LienDisciplineCategorieCritere $critere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'unite' => 'required|string|min:1|max:255',
            'min' => 'nullable|integer',
            'max' => 'nullable|integer',
        ]);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie'])->findOrFail($critere->id);

        $discCatCritere->update([
            'unite' => $request->unite,
            'min' => $request->min,
            'max' => $request->max,
        ]);

        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Unité du critère mis à jour');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienDisciplineCategorieCritere $lienDisciplineCategorieCritere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discCatCritere = LienDisciplineCategorieCritere::with(['discipline', 'categorie', 'valeurs'])->findOrFail($lienDisciplineCategorieCritere->id);

        if($discCatCritere->valeurs->isNotEmpty()) {
            foreach ($discCatCritere->valeurs as $valeur) {
                $valeur->delete();
            }
        }

        $discCatCritere->delete();
        return to_route('admin.disciplines.categories.criteres.edit', ['discipline' => $discCatCritere->discipline->slug, 'categorie' => $discCatCritere->categorie])->with('success', 'Critère et valeurs associés supprimés');

    }

    public function duplicate(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validatedData = $request->validate([
            'discipline_origin' => ['required', 'exists:liste_disciplines,slug'],
            'discipline_target' => ['required', 'exists:liste_disciplines,slug'],
        ]);

        $dis_origin = ListDiscipline::with(['categories'])->where('slug', $validatedData['discipline_origin'])->firstOrFail();
        $dis_target = ListDiscipline::with(['categories'])->where('slug', $validatedData['discipline_target'])->firstOrFail();

        foreach ($dis_origin->categories as $category) {
            $originPivotAttributes = $category->pivot;

            $targetCategoriesWithPivot = $dis_target->categories;

            // Check if the target discipline's categories contain the same categorie_id as the origin category
            $categoryExistsInTarget = $targetCategoriesWithPivot->contains(function ($targetCategory) use ($originPivotAttributes) {
                return $targetCategory->pivot->categorie_id === $originPivotAttributes->categorie_id;
            });

            if (!$categoryExistsInTarget) {

                $pivotAttributes = collect($originPivotAttributes)->except(['id','discipline_id', 'categorie_id'])->toArray();

                $pivotAttributes['slug'] = Str::slug($category->nom) . '-' . $category->id . '_random_texte';

                $dis_target->categories()->attach($category->id, $pivotAttributes);

                $pivotTarget = $dis_target->categories()->where('categorie_id', $category->id)->first()->pivot;

                if ($pivotTarget) {
                    $newSlug = Str::slug($category->nom) . '-' . $pivotTarget->id;
                    $dis_target->categories()->updateExistingPivot($category->id, ['slug' => $newSlug]);

                    if($originPivotAttributes->criteres) {
                        foreach($originPivotAttributes->criteres as $critere) {
                            $critereForTarget = $pivotTarget->criteres()->create([
                                'categorie_id' => $pivotTarget->id,
                                'discipline_id' => $pivotTarget->discipline_id,
                                'critere_id' => $critere->critere_id,
                                'nom' => $critere->nom,
                                'type_champ_form' => $critere->type_champ_form,
                                'type_champ_id' => $critere->type_champ_id,
                                'unite' => $critere->unite,
                                'min' => $critere->min,
                                'max' => $critere->max,
                                'ordre' => $critere->ordre,
                                'visible_back' => $critere->visible_back,
                                'visible_front' => $critere->visible_front,
                                'visible_block' => $critere->visible_block,
                                'indexable' => $critere->indexable
                            ]);

                            if($critere->valeurs) {
                                foreach($critere->valeurs as $valeur) {
                                    $critValeur = $critereForTarget->valeurs()->create([
                                        'valeur' => $valeur->valeur,
                                        'ordre' => $valeur->ordre,
                                        'defaut' => $valeur->defaut,
                                        'inclus_all' => $valeur->inclus_all
                                    ]);
                                    if($valeur->sous_criteres) {
                                        foreach($valeur->sous_criteres as $sousCritere) {
                                            $sousCrit = $critValeur->sous_criteres()->create([
                                                'nom' => $sousCritere->nom,
                                                'type_champ_form' => $sousCritere->type_champ_form,
                                                'type_champ_id' => $sousCritere->type_champ_id,
                                                'unite' => $sousCritere->unite,
                                                'min' => $sousCritere->min,
                                                'max' => $sousCritere->max,
                                            ]);
                                            if($sousCritere->sous_criteres_valeurs) {
                                                foreach($sousCritere->sous_criteres_valeurs as $sousCritValeur) {
                                                    $sousCrit->sous_criteres_valeurs()->create([
                                                        'valeur' => $sousCritValeur->valeur,
                                                        'ordre' => $sousCritValeur->ordre,
                                                        'defaut' => $sousCritValeur->defaut,
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // updateOrCreate criteres and tarifs for existing categories
                $pivotTarget = $dis_target->categories()->where('categorie_id', $category->id)->first()->pivot;
                if ($pivotTarget) {
                    if($originPivotAttributes->criteres) {
                        foreach($originPivotAttributes->criteres as $critere) {
                            $critereForTarget = $pivotTarget->criteres()->updateOrCreate(
                                [
                                    'categorie_id' => $pivotTarget->id,
                                    'discipline_id' => $pivotTarget->discipline_id,
                                    'critere_id' => $critere->critere_id
                                ],
                                [
                                    'nom' => $critere->nom,
                                    'type_champ_form' => $critere->type_champ_form,
                                    'type_champ_id' => $critere->type_champ_id,
                                    'unite' => $critere->unite,
                                'min' => $critere->min,
                                'max' => $critere->max,
                                    'ordre' => $critere->ordre,
                                    'visible_back' => $critere->visible_back,
                                    'visible_front' => $critere->visible_front,
                                    'visible_block' => $critere->visible_block,
                                    'indexable' => $critere->indexable
                                ]
                            );

                            if($critere->valeurs) {
                                foreach($critere->valeurs as $valeur) {
                                    $critValeur = $critereForTarget->valeurs()->updateOrCreate(
                                        [
                                            'discipline_categorie_critere_id' => $critereForTarget->id,
                                            'valeur' => $valeur->valeur,
                                        ],
                                        [
                                            'ordre' => $valeur->ordre,
                                            'defaut' => $valeur->defaut,
                                            'inclus_all' => $valeur->inclus_all
                                        ]
                                    );
                                    if($valeur->sous_criteres) {
                                        foreach($valeur->sous_criteres as $sousCritere) {
                                            $sousCrit = $critValeur->sous_criteres()->updateOrCreate(
                                                [
                                                    'dis_cat_crit_val_id' => $critValeur->id,
                                                    'nom' => $sousCritere->nom
                                                ],
                                                [
                                                    'type_champ_form' => $sousCritere->type_champ_form,
                                                    'type_champ_id' => $sousCritere->type_champ_id,
                                                    'unite' => $sousCritere->unite,
                                                    'min' => $sousCritere->min,
                                                    'max' => $sousCritere->max,
                                                ]
                                            );
                                            if($sousCritere->sous_criteres_valeurs) {
                                                foreach($sousCritere->sous_criteres_valeurs as $sousCritValeur) {
                                                    $sousCrit->sous_criteres_valeurs()->updateOrCreate(
                                                        [
                                                            'dcc_val_ss_crit_id' => $sousCrit->id,
                                                            'valeur' => $sousCritValeur->valeur
                                                        ],
                                                        [
                                                            'ordre' => $sousCritValeur->ordre,
                                                            'defaut' => $sousCritValeur->defaut,
                                                        ]
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return to_route('admin.disciplines.index')->with('success', 'Les catégories, critères et valeurs de la discipline '. $dis_origin->name .' a été dupliquée à '. $dis_target->name .'.');
    }

    public function duplicateOneCategorie(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validatedData = $request->validate([
            'discipline_origin' => ['required', 'exists:liste_disciplines,slug'],
            'categorie_origin' => ['required', 'exists:categories,id'],
            'discipline_target' => ['required', 'exists:liste_disciplines,slug'],
        ]);

        $dis_origin = ListDiscipline::with(['categories'])
                    ->where('slug', $validatedData['discipline_origin'])
                    ->firstOrFail();

        $cat_origin = Categorie::findOrFail($validatedData['categorie_origin']);

        $dis_target = ListDiscipline::with(['categories'])
                    ->where('slug', $validatedData['discipline_target'])
                    ->firstOrFail();

        $disCatOrigin = $dis_origin->categories()->where('categorie_id', $cat_origin->id)->first()->pivot;
        $targetCategoriesWithPivot = $dis_target->categories;

        // Check if the target discipline's categories contain the same categorie_id as the origin category
        $categoryExistsInTarget = $targetCategoriesWithPivot->contains(function ($targetCategory) use ($disCatOrigin) {
            return $targetCategory->pivot->categorie_id === $disCatOrigin->categorie_id;
        });

        if (!$categoryExistsInTarget) {

            $pivotAttributes = collect($disCatOrigin)->except(['id','discipline_id', 'categorie_id'])->toArray();

            $pivotAttributes['slug'] = Str::slug($cat_origin->nom) . '-' . $cat_origin->id . '_random_texte';

            $dis_target->categories()->attach($cat_origin->id, $pivotAttributes);

            $pivotTarget = $dis_target->categories()->where('categorie_id', $cat_origin->id)->first()->pivot;

            if ($pivotTarget) {
                $newSlug = Str::slug($cat_origin->nom) . '-' . $pivotTarget->id;
                $dis_target->categories()->updateExistingPivot($cat_origin->id, ['slug' => $newSlug]);

                if($disCatOrigin->criteres) {
                    foreach($disCatOrigin->criteres as $critere) {
                        $critereForTarget = $pivotTarget->criteres()->create([
                            'categorie_id' => $pivotTarget->id,
                            'discipline_id' => $pivotTarget->discipline_id,
                            'critere_id' => $critere->critere_id,
                            'nom' => $critere->nom,
                            'type_champ_form' => $critere->type_champ_form,
                            'type_champ_id' => $critere->type_champ_id,
                            'unite' => $critere->unite,
                            'min' => $critere->min,
                            'max' => $critere->max,
                            'ordre' => $critere->ordre,
                            'visible_back' => $critere->visible_back,
                            'visible_front' => $critere->visible_front,
                            'visible_block' => $critere->visible_block,
                            'indexable' => $critere->indexable
                        ]);

                        if($critere->valeurs) {
                            foreach($critere->valeurs as $valeur) {
                                $critValeur = $critereForTarget->valeurs()->create([
                                    'valeur' => $valeur->valeur,
                                    'ordre' => $valeur->ordre,
                                    'defaut' => $valeur->defaut,
                                    'inclus_all' => $valeur->inclus_all
                                ]);
                                if($valeur->sous_criteres) {
                                    foreach($valeur->sous_criteres as $sousCritere) {
                                        $sousCrit = $critValeur->sous_criteres()->create([
                                            'nom' => $sousCritere->nom,
                                            'type_champ_form' => $sousCritere->type_champ_form,
                                            'type_champ_id' => $sousCritere->type_champ_id,
                                            'unite' => $sousCritere->unite,
                                            'min' => $sousCritere->min,
                                            'max' => $sousCritere->max,
                                        ]);
                                        if($sousCritere->sous_criteres_valeurs) {
                                            foreach($sousCritere->sous_criteres_valeurs as $sousCritValeur) {
                                                $sousCrit->sous_criteres_valeurs()->create([
                                                    'valeur' => $sousCritValeur->valeur,
                                                    'ordre' => $sousCritValeur->ordre,
                                                    'defaut' => $sousCritValeur->defaut,
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        } else {
            // updateOrCreate criteres and tarifs for existing categories
            $pivotTarget = $dis_target->categories()->where('categorie_id', $cat_origin->id)->first()->pivot;
            if ($pivotTarget) {
                if($disCatOrigin->criteres) {
                    foreach($disCatOrigin->criteres as $critere) {
                        $critereForTarget = $pivotTarget->criteres()->updateOrCreate(
                            [
                                'categorie_id' => $pivotTarget->id,
                                'discipline_id' => $pivotTarget->discipline_id,
                                'critere_id' => $critere->critere_id
                            ],
                            [
                                'nom' => $critere->nom,
                                'type_champ_form' => $critere->type_champ_form,
                                'type_champ_id' => $critere->type_champ_id,
                                'unite' => $critere->unite,
                                'min' => $critere->min,
                                'max' => $critere->max,
                                'ordre' => $critere->ordre,
                                'visible_back' => $critere->visible_back,
                                'visible_front' => $critere->visible_front,
                                'visible_block' => $critere->visible_block,
                                'indexable' => $critere->indexable
                            ]
                        );

                        if($critere->valeurs) {
                            foreach($critere->valeurs as $valeur) {
                                $critValeur = $critereForTarget->valeurs()->updateOrCreate(
                                    [
                                        'discipline_categorie_critere_id' => $critereForTarget->id,
                                        'valeur' => $valeur->valeur,
                                    ],
                                    [
                                        'ordre' => $valeur->ordre,
                                        'defaut' => $valeur->defaut,
                                        'inclus_all' => $valeur->inclus_all
                                    ]
                                );
                                if($valeur->sous_criteres) {
                                    foreach($valeur->sous_criteres as $sousCritere) {
                                        $sousCrit = $critValeur->sous_criteres()->updateOrCreate(
                                            [
                                                'dis_cat_crit_val_id' => $critValeur->id,
                                                'nom' => $sousCritere->nom
                                            ],
                                            [
                                                'type_champ_form' => $sousCritere->type_champ_form,
                                                'type_champ_id' => $sousCritere->type_champ_id,
                                                'unite' => $sousCritere->unite,
                                                'min' => $sousCritere->min,
                                                'max' => $sousCritere->max,
                                            ]
                                        );
                                        if($sousCritere->sous_criteres_valeurs) {
                                            foreach($sousCritere->sous_criteres_valeurs as $sousCritValeur) {
                                                $sousCrit->sous_criteres_valeurs()->updateOrCreate(
                                                    [
                                                        'dcc_val_ss_crit_id' => $sousCrit->id,
                                                        'valeur' => $sousCritValeur->valeur
                                                    ],
                                                    [
                                                        'ordre' => $sousCritValeur->ordre,
                                                        'defaut' => $sousCritValeur->defaut,
                                                    ]
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return to_route('admin.disciplines.index')->with('success', 'La catégorie '. $cat_origin->nom .', ses critères et valeurs de la discipline '. $dis_origin->name .' ont été dupliqués à '. $dis_target->name .'.');
    }
}
