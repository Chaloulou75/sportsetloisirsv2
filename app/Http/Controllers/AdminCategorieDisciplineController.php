<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Http\RedirectResponse;

class AdminCategorieDisciplineController extends Controller
{
    public function duplicate(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validatedData = $request->validate([
            'discipline_origin' => ['required', 'exists:liste_disciplines,slug'],
            'discipline_target' => ['required', 'exists:liste_disciplines,slug'],
        ]);

        $dis_origin = ListDiscipline::with('categories')->where('slug', $validatedData['discipline_origin'])->firstOrFail();
        $dis_target = ListDiscipline::with('categories')->where('slug', $validatedData['discipline_target'])->firstOrFail();

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

                $pivot = $dis_target->categories()->where('categorie_id', $category->id)->first()->pivot;

                if ($pivot) {
                    $newSlug = Str::slug($category->nom) . '-' . $pivot->id;
                    $dis_target->categories()->updateExistingPivot($category->id, ['slug' => $newSlug]);
                }
            }
        }

        return to_route('admin.disciplines.index')->with('success', 'Les catégories de la discipline '. $dis_origin->name .' a été dupliquée à '. $dis_target->name .'.');
    }

    public function duplicateAllParameters(Request $request): RedirectResponse
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
                                                'type_champ_form' => $sousCritere->type_champ_form
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
                                                    'type_champ_form' => $sousCritere->type_champ_form
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

        return to_route('admin.disciplines.index')->with('success', 'Les catégories, critères et valeurs de la discipline '. $dis_origin->name .' a été dupliquée à '. $dis_target->name .'.');
    }
}
