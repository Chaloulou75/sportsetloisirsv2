<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\TypeChamp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Validation\Rule;
use App\Models\LienDisCatTariftype;
use Illuminate\Http\RedirectResponse;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisCatTarBookingField;

class LienDisCatTarBookingFieldController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
            'type_champ.id' => ['required', Rule::exists(TypeChamp::class, 'id')],
            'type_champ.type' => ['required', 'String'],
        ]);

        $tarifType->tarif_booking_fields()->create([
            'nom' => $request->nom,
            'type_champ_form' => $request->type_champ['type'],
            'type_champ_id' => $request->type_champ['id'],
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'champ ajouté au tarif');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $bookingfield->update([
            'nom' => $request->nom,
        ]);

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'champ modifié au tarif');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListDiscipline $discipline, LienDisciplineCategorie $categorie, LienDisCatTariftype $tarifType, LienDisCatTarBookingField $bookingfield): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $bookingfield->delete();

        return to_route('admin.disciplines.categories.tarifs.edit', ['discipline' => $discipline, 'categorie' => $categorie, 'tarifType' => $tarifType])->with('success', 'champ supprimé');
    }

    public function duplicate(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);
        $validatedData = $request->validate([
            'discipline_origin' => ['required', 'exists:liste_disciplines,slug'],
            'categorie_origin' => ['required', 'exists:categories,id'],
            'cat_tarif_origin' => ['required', 'exists:liens_dis_cat_tariftypes,id'],
            'discipline_target' => ['required', 'exists:liste_disciplines,slug'],
        ]);

        $dis_origin = ListDiscipline::with(['categories'])
                    ->where('slug', $validatedData['discipline_origin'])
                    ->firstOrFail();

        $cat_origin = Categorie::findOrFail($validatedData['categorie_origin']);

        $catTarOrigin = LienDisCatTariftype::with('tarif_booking_fields')->findOrFail($validatedData['cat_tarif_origin']);

        $dis_target = ListDiscipline::with(['categories'])
                    ->where('slug', $validatedData['discipline_target'])
                    ->firstOrFail();

        $disCatOrigin = $dis_origin->categories()->where('categorie_id', $cat_origin->id)->first()->pivot;
        $targetCategoriesWithPivot = $dis_target->categories;

        // Check if the target discipline's categorie contain the same categorie_id as the origin category
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

                if($disCatOrigin->tarif_types && $catTarOrigin) {

                    $catTarifType = $pivotTarget->tarif_types()->create([
                        'discipline_id' => $dis_target->id,
                        'tarif_type_id' => $catTarOrigin->tarif_type_id,
                        'nom' => $catTarOrigin->nom,
                        'show_planning' => $catTarOrigin->show_planning
                    ]);
                    if($catTarOrigin->tarif_booking_fields) {
                        foreach($catTarOrigin->tarif_booking_fields as $bookingField) {
                            $catTarBookingField = $catTarifType->tarif_booking_fields()->create([
                                'nom' => $bookingField->nom,
                                'type_champ_form' => $bookingField->type_champ_form,
                                'type_champ_id' => $bookingField->type_champ_id,
                                'ordre' => $bookingField->ordre,
                            ]);
                            if($bookingField->valeurs) {
                                foreach($bookingField->valeurs as $val) {
                                    $catTarBookVal = $catTarBookingField->valeurs()->create([
                                         'valeur' => $val->valeur,
                                         'ordre' => $val->ordre,
                                     ]);

                                    if($val->sous_fields) {
                                        foreach ($val->sous_fields as $ssField) {
                                            $ssFieldBooking = $catTarBookVal->sous_fields()->create([
                                                'nom' => $ssField->nom,
                                                'type_champ_form' => $ssField->type_champ_form,
                                                'type_champ_id' => $ssField->type_champ_id,
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
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // updateOrCreate tarifs if existing categorie
            $pivotTarget = $dis_target->categories()->where('categorie_id', $cat_origin->id)->first()->pivot;
            if ($pivotTarget) {
                if($disCatOrigin->tarif_types && $catTarOrigin) {
                    $catTarifType = $pivotTarget->tarif_types()->updateOrCreate(
                        [
                            'discipline_id' => $dis_target->id,
                            'tarif_type_id' => $catTarOrigin->tarif_type_id
                        ],
                        [
                            'nom' => $catTarOrigin->nom,
                            'show_planning' => $catTarOrigin->show_planning
                        ]
                    );
                    if($catTarOrigin->tarif_booking_fields) {
                        foreach($catTarOrigin->tarif_booking_fields as $bookingField) {
                            $catTarBookingField = $catTarifType->tarif_booking_fields()->updateOrCreate(
                                [
                                    'cat_tarif_id' => $catTarifType->id,
                                    'nom' => $bookingField->nom,
                                ],
                                [
                                    'type_champ_form' => $bookingField->type_champ_form,
                                    'type_champ_id' => $bookingField->type_champ_id,
                                    'ordre' => $bookingField->ordre,
                                ]
                            );
                            if($bookingField->valeurs) {
                                foreach($bookingField->valeurs as $val) {
                                    $catTarBookVal = $catTarBookingField->valeurs()->updateOrCreate(
                                        [
                                             'cat_tar_field_id' => $catTarBookingField->id,
                                             'valeur' => $val->valeur,
                                         ],
                                        [
                                             'ordre' => $val->ordre,
                                         ]
                                    );

                                    if($val->sous_fields) {
                                        foreach ($val->sous_fields as $ssField) {
                                            $ssFieldBooking = $catTarBookVal->sous_fields()->updateOrCreate(
                                                [
                                                    'field_valeur_id' => $catTarBookVal->id,
                                                    'nom' => $ssField->nom,
                                                ],
                                                [
                                                    'type_champ_form' => $ssField->type_champ_form,
                                                    'type_champ_id' => $ssField->type_champ_id,
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

                                }
                            }
                        }
                    }
                }
            }
        }

        return to_route('admin.disciplines.index')->with('success', 'La catégorie, tarif et champs de formulaire associés à la discipline '. $dis_origin->name .' ont été dupliqués à la discipline '. $dis_target->name .'.');
    }
}
