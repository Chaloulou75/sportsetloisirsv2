<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use App\Models\ListeTarifType;
use Illuminate\Validation\Rule;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;

class LienDisCatTariftypeController extends Controller
{
    /**
     * Show the index.
     */
    public function index(ListDiscipline $discipline, LienDisciplineCategorie $categorie): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);

        $categorie = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs',
        ])
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->findOrFail($categorie->id);

        $categories = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'tarif_types',
            'tarif_types.tarif_attributs.sous_attributs.valeurs',
            'tarif_types.tarif_attributs.valeurs',
        ])->where('discipline_id', $discipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

        $listeTarifsTypes = ListeTarifType::with('tariftypeattributs')->select(['id', 'type'])->get();

        return Inertia::render('Admin/Disciplines/Categories/Tarifs/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categorie' => fn () => $categorie,
            'categories' => fn () => $categories,
            'discipline' => fn () => $discipline,
            'listeTarifsTypes' => fn () => $listeTarifsTypes,
        ]);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'type.id' =>  ['required', Rule::exists('liste_tarifs_types', 'id')],
            'nom' => ['nullable', 'string', 'min:3', 'max:255'],
        ]);

        $categorie->tarif_types()->create([
            'discipline_id' => $discipline->id,
            'tarif_type_id' => $request->type['id'],
            'nom' => $request->nom ?? $request->type['type'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Type de tarif ajouté');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $discipline = ListDiscipline::with('familles')->findOrFail($discipline->id);
        $tarifType = LienDisCatTariftype::with(
            'tarif_booking_fields',
            'tarif_booking_fields.valeurs',
            'tarif_booking_fields.sous_fields',
            'tarif_booking_fields.sous_fields.valeurs',
        )->find($tarifType->id);

        $categorie = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'tarif_types',
        ])
        ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
        ->findOrFail($categorie->id);

        $categories = LienDisciplineCategorie::with([
            'discipline',
            'categorie',
            'tarif_types',
        ])->where('discipline_id', $discipline->id)
            ->select(['id', 'slug', 'discipline_id', 'categorie_id', 'nom_categorie_pro', 'nom_categorie_client'])
            ->get();

        return Inertia::render('Admin/Disciplines/Categories/Tarifs/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categorie' => fn () => $categorie,
            'categories' => fn () => $categories,
            'discipline' => fn () => $discipline,
            'tarifType' => fn () => $tarifType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => 'required|string|min:3',
        ]);

        $tarifType->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Nom du type de tarif mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $tarifType->delete();

        return to_route('admin.disciplines.categories.tarifs.index', ['discipline' => $discipline, 'categorie' => $categorie])->with('success', 'Type de tarif supprimé');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update_show_planning(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'show_planning' => 'boolean',
        ]);

        $tarifType->update([
            'show_planning' => $request->show_planning,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'champ ajouté au tarif');
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

                    if($originPivotAttributes->tarif_types) {
                        foreach($originPivotAttributes->tarif_types as $tarifType) {
                            $catTarifType = $pivotTarget->tarif_types()->create([
                                'discipline_id' => $dis_target->id,
                                'tarif_type_id' => $tarifType->tarif_type_id,
                                'nom' => $tarifType->nom,
                                'show_planning' => $tarifType->show_planning
                            ]);
                            if($tarifType->tarif_attributs) {
                                foreach($tarifType->tarif_attributs as $attribut) {
                                    $catTarAtt = $catTarifType->tarif_attributs()->create([
                                        'nom' => $attribut->nom,
                                        'type_champ_form' => $attribut->type_champ_form,
                                        'ordre' => $attribut->ordre,
                                    ]);
                                    if($attribut->valeurs) {
                                        foreach($attribut->valeurs as $valeur) {
                                            $catTarAttValeur = $catTarAtt->valeurs()->create([
                                                'valeur' => $valeur->valeur,
                                                'ordre' => $valeur->ordre,
                                            ]);
                                            if($valeur->sous_attributs) {
                                                foreach($valeur->sous_attributs as $ssAttr) {
                                                    $catTarAttValSsAttr = $catTarAttValeur->sous_attributs()->create([
                                                        'att_valeur_id' => $ssAttr->att_valeur_id,
                                                        'nom' => $ssAttr->nom,
                                                        'type_champ_form' => $ssAttr->type_champ_form,
                                                        'ordre' => $ssAttr->ordre,
                                                    ]);
                                                    if($ssAttr->valeurs) {
                                                        foreach ($ssAttr->valeurs as $ssAttrVal) {
                                                            $catTarAttValSsAttr->valeurs()->create([
                                                                'valeur' => $ssAttrVal->valeur,
                                                                'ordre' => $ssAttrVal->ordre,
                                                                'inclus_all' => $ssAttrVal->inclus_all
                                                            ]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($attribut->sous_attributs) {
                                        foreach($attribut->sous_attributs as $ssAttribut) {
                                            $catTarAttSsAttr = $catTarAtt->sous_attributs()->create([
                                                'att_valeur_id' => $ssAttribut->att_valeur_id,
                                                'nom' => $ssAttribut->nom,
                                                'type_champ_form' => $ssAttribut->type_champ_form,
                                                'ordre' => $ssAttribut->ordre,
                                            ]);
                                            if($ssAttribut->valeurs) {
                                                foreach ($ssAttribut->valeurs as $sousAttrVal) {
                                                    $catTarAttSsAttr->valeurs()->create([
                                                        'valeur' => $sousAttrVal->valeur,
                                                        'ordre' => $sousAttrVal->ordre,
                                                        'inclus_all' => $sousAttrVal->inclus_all
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if($tarifType->tarif_booking_fields) {
                                foreach($tarifType->tarif_booking_fields as $bookingField) {
                                    $catTarBookingField = $catTarifType->tarif_booking_fields()->create([
                                        'nom' => $bookingField->nom,
                                        'type_champ_form' => $bookingField->type_champ_form,
                                        'ordre' => $bookingField->ordre,
                                    ]);
                                    if($bookingField->sous_fields) {
                                        foreach ($bookingField->sous_fields as $ssField) {
                                            $ssFieldBooking = $catTarBookingField->sous_fields()->create([
                                                'nom' => $ssField->nom,
                                                'type_champ_form' => $ssField->type_champ_form,
                                                'ordre' => $ssField->ordre,
                                            ]);
                                            if($ssField->valeurs) {
                                                foreach($ssField->valeurs as $value) {
                                                    $ssFieldBooking->valeurs()->create([
                                                        'valeur' => $value->valeur,
                                                        'ordre' => $value->ordre,
                                                        'inclus_all' => $value->inclus_all
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                    if($bookingField->valeurs) {
                                        foreach($bookingField->valeurs as $val) {
                                            $catTarBookingField->valeurs()->create([
                                                'valeur' => $val->valeur,
                                                'ordre' => $val->ordre,
                                            ]);
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
                    if($originPivotAttributes->tarif_types) {
                        foreach($originPivotAttributes->tarif_types as $tarifType) {
                            $catTarifType = $pivotTarget->tarif_types()->updateOrCreate(
                                [
                                    'discipline_id' => $dis_target->id,
                                    'tarif_type_id' => $tarifType->tarif_type_id
                                ],
                                [
                                    'nom' => $tarifType->nom,
                                    'show_planning' => $tarifType->show_planning
                                ]
                            );
                            if($tarifType->tarif_attributs) {
                                foreach($tarifType->tarif_attributs as $attribut) {
                                    $catTarAtt = $catTarifType->tarif_attributs()->updateOrCreate(
                                        [
                                            'cat_tarif_id' => $catTarifType->id,
                                            'nom' => $attribut->nom
                                        ],
                                        [
                                            'type_champ_form' => $attribut->type_champ_form,
                                            'ordre' => $attribut->ordre,
                                        ]
                                    );
                                    if($attribut->valeurs) {
                                        foreach($attribut->valeurs as $valeur) {
                                            $catTarAttValeur = $catTarAtt->valeurs()->updateOrCreate(
                                                [
                                                    'cat_tar_att_id' => $catTarAtt->id,
                                                    'valeur' => $valeur->valeur
                                                ],
                                                [
                                                    'ordre' => $valeur->ordre,
                                                ]
                                            );
                                            if($valeur->sous_attributs) {
                                                foreach($valeur->sous_attributs as $ssAttr) {
                                                    $catTarAttValSsAttr = $catTarAttValeur->sous_attributs()->updateOrCreate(
                                                        [
                                                            'cat_tar_att_valeur_id' => $catTarAttValeur->id,
                                                            'att_valeur_id' => $ssAttr->att_valeur_id,
                                                            'nom' => $ssAttr->nom
                                                        ],
                                                        [
                                                            'type_champ_form' => $ssAttr->type_champ_form,
                                                            'ordre' => $ssAttr->ordre,
                                                        ]
                                                    );
                                                    if($ssAttr->valeurs) {
                                                        foreach ($ssAttr->valeurs as $ssAttrVal) {
                                                            $catTarAttValSsAttr->valeurs()->updateOrCreate(
                                                                [
                                                                    'sousattribut_id' => $catTarAttValSsAttr->id,
                                                                    'valeur' => $ssAttrVal->valeur
                                                                ],
                                                                [
                                                                    'ordre' => $ssAttrVal->ordre,
                                                                    'inclus_all' => $ssAttrVal->inclus_all
                                                                ]
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($attribut->sous_attributs) {
                                        foreach($attribut->sous_attributs as $ssAttribut) {
                                            $catTarAttSsAttr = $catTarAtt->sous_attributs()->updateOrCreate(
                                                [
                                                    'cat_tar_att_id' => $catTarAtt->id,
                                                    'att_valeur_id' => $ssAttribut->att_valeur_id,
                                                    'nom' => $ssAttribut->nom
                                                ],
                                                [
                                                    'type_champ_form' => $ssAttribut->type_champ_form,
                                                    'ordre' => $ssAttribut->ordre,
                                                ]
                                            );
                                            if($ssAttribut->valeurs) {
                                                foreach ($ssAttribut->valeurs as $sousAttrVal) {
                                                    $catTarAttSsAttr->valeurs()->updateOrCreate(
                                                        [   'sousattribut_id' => $catTarAttSsAttr->id,
                                                            'valeur' => $sousAttrVal->valeur,
                                                        ],
                                                        [
                                                            'ordre' => $sousAttrVal->ordre,
                                                            'inclus_all' => $sousAttrVal->inclus_all
                                                        ]
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if($tarifType->tarif_booking_fields) {
                                foreach($tarifType->tarif_booking_fields as $bookingField) {
                                    $catTarBookingField = $catTarifType->tarif_booking_fields()->updateOrCreate(
                                        [
                                            'cat_tarif_id' => $catTarifType->id,
                                            'nom' => $bookingField->nom,
                                        ],
                                        [
                                            'type_champ_form' => $bookingField->type_champ_form,
                                            'ordre' => $bookingField->ordre,
                                        ]
                                    );
                                    if($bookingField->sous_fields) {
                                        foreach ($bookingField->sous_fields as $ssField) {
                                            $ssFieldBooking = $catTarBookingField->sous_fields()->updateOrCreate(
                                                [
                                                    'booking_field_id' => $catTarBookingField->id,
                                                    'nom' => $ssField->nom,
                                                ],
                                                [
                                                    'type_champ_form' => $ssField->type_champ_form,
                                                    'ordre' => $ssField->ordre,
                                                ]
                                            );
                                            if($ssField->valeurs) {
                                                foreach($ssField->valeurs as $value) {
                                                    $ssFieldBooking->valeurs()->updateOrCreate(
                                                        [
                                                            'sousfield_id' => $ssFieldBooking->id,
                                                            'valeur' => $value->valeur
                                                        ],
                                                        [
                                                            'ordre' => $value->ordre,
                                                            'inclus_all' => $value->inclus_all
                                                        ]
                                                    );
                                                }
                                            }
                                        }
                                    }
                                    if($bookingField->valeurs) {
                                        foreach($bookingField->valeurs as $val) {
                                            $catTarBookingField->valeurs()->updateOrCreate(
                                                [
                                                    'cat_tar_field_id' => $catTarBookingField->id,
                                                    'valeur' => $val->valeur,
                                                ],
                                                [
                                                    'ordre' => $val->ordre,
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


        return to_route('admin.disciplines.index')->with('success', 'Les catégories, tarifs, et champs de formulaire associés à la discipline '. $dis_origin->name .' ont été dupliquées à '. $dis_target->name .'.');

    }
}
