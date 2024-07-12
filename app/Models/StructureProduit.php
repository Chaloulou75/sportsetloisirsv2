<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StructureProduit extends Model
{
    use HasFactory;

    protected $table = 'structures_produits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'actif' => 'boolean',
        'reservable' => 'boolean'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['cat_tarifs'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['minimum_amount'];

    protected function minimumAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->cat_tarifs->isNotEmpty()
                    ? $this->cat_tarifs->min('amount')
                    : null;
            }
        );
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function adresse(): BelongsTo
    {
        return $this->belongsTo(StructureAddress::class, 'lieu_id');
    }

    public function criteres(): HasMany
    {
        return $this->hasMany(StructureProduitCritere::class, 'produit_id');
    }

    public function sous_criteres(): HasMany
    {
        return $this->hasMany(StructureProduitSousCritere::class, 'produit_id');
    }

    public function cat_tarifs(): BelongsToMany
    {
        return $this->belongsToMany(StructureCatTarif::class, 'produit_cat_tarif', 'produit_id', 'cat_tarif_id');
    }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'produit_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ProductReservation::class, 'produit_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'adresse',
            'discipline:id,name,slug,theme',
            'categorie',
            'activite:id,titre',
            'structure:id,name,slug',
            'criteres:id,activite_id,produit_id,critere_id,valeur_id,valeur',
            'criteres.critere',
            'criteres.critere_valeur',
            'criteres.critere_valeur.sous_criteres',
            'criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs.sous_critere_valeur',
            'criteres.sous_criteres',
            'criteres.sous_criteres.sous_critere',
            'criteres.sous_criteres.sous_critere_valeur',
            'cat_tarifs',
            'cat_tarifs.produits:id',
            'cat_tarifs.categorie',
            'cat_tarifs.cat_tarif_type',
            'cat_tarifs.cat_tarif_type.tarif_attributs',
            'cat_tarifs.cat_tarif_type.tarif_attributs.valeurs',
            'cat_tarifs.cat_tarif_type.tarif_attributs.sous_attributs',
            'cat_tarifs.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
            'cat_tarifs.cat_tarif_type.tarif_booking_fields',
            'cat_tarifs.cat_tarif_type.tarif_booking_fields.valeurs',
            'cat_tarifs.cat_tarif_type.tarif_booking_fields.sous_fields',
            'cat_tarifs.cat_tarif_type.tarif_booking_fields.sous_fields.valeurs',
            'cat_tarifs.attributs',
            'cat_tarifs.attributs.tarif_attribut',
            'cat_tarifs.attributs.tarif_attribut.valeurs',
            'cat_tarifs.attributs.sous_attributs',
            'cat_tarifs.attributs.sous_attributs.sous_attribut',
            'cat_tarifs.attributs.sous_attributs.sous_attribut_valeur',
            'plannings' => function ($query) {
                $query->endNotPassed()->orderByDateStart();
            },
        ]);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['crit'] ?? null, function ($query, $criteresBase) {
            foreach ($criteresBase as $critereId => $selectedCritere) {

                if ($this->isEmptyCritere($selectedCritere) || (isset($selectedCritere['inclus_all']) && $selectedCritere['inclus_all'] === true)) {
                    continue;
                }

                $query->whereHas('criteres', function ($q) use ($critereId, $selectedCritere) {
                    $q->where('critere_id', $critereId);
                    $this->applyFilterBasedOnType($q, $selectedCritere, $critereId);
                });

            }
        })->when($filters['ssCrit'] ?? null, function ($query, $sousCriteres) {
            foreach ($sousCriteres as $sousCritereId => $selectedSousCritere) {
                if ($selectedSousCritere === null) {
                    continue;
                }

                $query->whereHas('criteres.sous_criteres', function ($q) use ($sousCritereId, $selectedSousCritere) {
                    $q->where('sous_critere_id', $sousCritereId);
                    $this->applySousCritereFilter($q, $selectedSousCritere, $sousCritereId);
                });
            }
        });
    }

    private function isEmptyCritere($selectedCritere): bool
    {
        return $selectedCritere === null || (is_array($selectedCritere) && count($selectedCritere) === 0);
    }

    private function getTypeChampForm($selectedCritere, $critereId)
    {
        $critere = LienDisciplineCategorieCritere::find($critereId);
        if (!$critere) {
            return null;
        }

        return $critere->type_champ_form;
    }

    private function applyFilterBasedOnType($query, $selectedCritere, $critereId): void
    {
        $typeChampForm = $this->getTypeChampForm($selectedCritere, $critereId);

        switch ($typeChampForm) {
            case 'select':
            case 'checkbox':
            case 'radio':
                $this->applyMultipleValuesFilter($query, $selectedCritere);
                break;
            case 'text':
            case 'number':
                $query->where('valeur', $selectedCritere);
                break;
            case 'range':
                $this->applyRangeFilter($query, $selectedCritere);
                break;
            case 'range multiple':
                $this->applyRangeMultipleFilter($query, $selectedCritere);
                break;
            case 'time':
                $this->applySingleTimeFilter($query, $selectedCritere);
                break;
            case 'times':
                $this->applyOpenTimesFilter($query, $selectedCritere);
                break;
            case 'date':
                $this->applySingleDateFilter($query, $selectedCritere);
                break;
            case 'dates':
                $this->applyOpenDaysFilter($query, $selectedCritere);
                break;
            case 'mois':
                $this->applyOpenMonthsFilter($query, $selectedCritere);
                break;
            default:
                $query->where('valeur', $selectedCritere);
        }
    }

    private function applyMultipleValuesFilter($query, $selectedCritere): void
    {
        if (is_array($selectedCritere)) {
            if (isset($selectedCritere['id'])) {
                $query->where('valeur_id', $selectedCritere['id']);
            } else {
                $query->where(function ($subQuery) use ($selectedCritere) {
                    foreach ($selectedCritere as $critereSet) {
                        if (is_array($critereSet) && isset($critereSet['id'])) {
                            $subQuery->orWhere('valeur_id', $critereSet['id']);
                        }
                    }
                });
            }
        }

    }

    private function applyRangeFilter($query, $selectedCritere): void
    {
        // ok
        if (isset($selectedCritere)) {
            $query->where('valeur', '<=', $selectedCritere);
        }
    }

    private function applyRangeMultipleFilter($query, $selectedCritere): void
    {
        if (isset($selectedCritere['min']) && isset($selectedCritere['max'])) {
            $minValue = $selectedCritere['min'];
            $maxValue = $selectedCritere['max'];

            $query->where(function ($q) use ($minValue, $maxValue) {
                $q->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(valeur, ',', 1), ',', -1) >= ?", [$minValue])
                  ->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(valeur, ',', -1), ',', -1) <= ?", [$maxValue]);
            });
        }
    }

    private function applySingleTimeFilter($query, $selectedCritere): void
    {
        // ok!
        $time = Carbon::parse($selectedCritere)->setTimezone('Europe/Paris')->format('H:i');
        $query->where('valeur', $time);
    }

    private function applyOpenTimesFilter($query, $selectedCritere): void
    {
        // ok??
        if (is_array($selectedCritere) && count($selectedCritere) === 2 && isset($selectedCritere['debut']) && isset($selectedCritere['fin'])) {

            $startTime = Carbon::parse($selectedCritere['debut'])->setTimezone('Europe/Paris')->format('H:i');
            $endTime = Carbon::parse($selectedCritere['fin'])->setTimezone('Europe/Paris')->format('H:i');
            $query->where(function ($query) use ($startTime, $endTime) {
                $query->whereRaw('JSON_UNQUOTE(valeur->"$[0]") <= ? AND JSON_UNQUOTE(valeur->"$[1]") >= ?', [$startTime, $endTime])
                      ->orWhereRaw('JSON_UNQUOTE(valeur->"$[0]") >= ? AND JSON_UNQUOTE(valeur->"$[1]") <= ?', [$startTime, $endTime])
                      ->orwhereRaw('TIME(valeur->"$[0]") <= ? AND TIME(valeur->"$[1]") >= ?', [$startTime, $endTime]);
            });
        }
    }

    private function applySingleDateFilter($query, $selectedCritere): void
    {
        //OK!
        if (!$selectedCritere) {
            return;
        }
        $selectedDate = Carbon::parse($selectedCritere)
                    ->setTimezone('Europe/Paris')
                    ->startOfDay();
        $formattedDate = $selectedDate->format('Y-m-d');
        $query->where('valeur', $formattedDate);
    }

    private function applyOpenDaysFilter($query, $selectedCritere): void
    {
        // OK!
        if (is_array($selectedCritere) && count($selectedCritere) === 2) {
            $startDate = Carbon::parse($selectedCritere[0])
                    ->setTimezone('Europe/Paris')
                    ->startOfDay();
            $formattedStartDate = $startDate->format('Y-m-d');

            $endDate = Carbon::parse($selectedCritere[1])
                    ->setTimezone('Europe/Paris')
                    ->startOfDay();
            $formattedEndDate = $endDate->format('Y-m-d');

            $query->where(function ($query) use ($formattedStartDate, $formattedEndDate) {
                $query->whereRaw('JSON_UNQUOTE(valeur->"$[0]") <= ? AND JSON_UNQUOTE(valeur->"$[1]") >= ?', [$formattedStartDate, $formattedEndDate ])
                      ->orWhereRaw('JSON_UNQUOTE(valeur->"$[0]") <= ? AND JSON_UNQUOTE(valeur->"$[1]") >= ?', [$formattedStartDate, $formattedEndDate])
                      ->orWhereRaw('JSON_UNQUOTE(valeur->"$[0]") >= ? AND JSON_UNQUOTE(valeur->"$[1]") <= ?', [$formattedStartDate, $formattedEndDate]);
            });
        }
    }

    private function applyOpenMonthsFilter($query, $selectedCritere): void
    {
        // ok
        if (is_array($selectedCritere) && count($selectedCritere) === 2) {
            $monthStart = Carbon::parse($selectedCritere['monthStart'])->startOfMonth()->format('Y-m-d');
            $monthEnd = Carbon::parse($selectedCritere['monthEnd'])->startOfMonth()->format('Y-m-d');
            $query->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereRaw('JSON_UNQUOTE(valeur->"$[0]") <= ? AND JSON_UNQUOTE(valeur->"$[1]") >= ?', [$monthStart, $monthEnd])
                      ->orWhereRaw('JSON_UNQUOTE(valeur->"$[0]") <= ? AND JSON_UNQUOTE(valeur->"$[1]") >= ?', [$monthStart, $monthEnd])
                      ->orWhereRaw('JSON_UNQUOTE(valeur->"$[0]") >= ? AND JSON_UNQUOTE(valeur->"$[1]") <= ?', [$monthStart, $monthEnd]);
            });
        }
    }

    private function applySousCritereFilter($query, $selectedSousCritere, $sousCritereId): void
    {
        $sousCritere = LiensDisCatCritValSsCrit::find($sousCritereId);
        if (!$sousCritere) {
            return;
        }

        $typeChampForm = $sousCritere->type_champ_form;

        switch ($typeChampForm) {
            case 'select':
            case 'checkbox':
            case 'radio':
                $this->applyMultipleValuesSousCritFilter($query, $selectedSousCritere);
                break;
            case 'text':
            case 'number':
                $query->where('valeur', $selectedSousCritere);
                break;
            case 'range':
                $this->applyRangeFilter($query, $selectedSousCritere);
                break;
            case 'range multiple':
                $this->applyRangeMultipleFilter($query, $selectedSousCritere);
                break;
            case 'time':
                $this->applySingleTimeFilter($query, $selectedSousCritere);
                break;
            case 'times':
                $this->applyOpenTimesFilter($query, $selectedSousCritere);
                break;
            case 'date':
                $this->applySingleDateFilter($query, $selectedSousCritere);
                break;
            case 'dates':
                $this->applyOpenDaysFilter($query, $selectedSousCritere);
                break;
            case 'mois':
                $this->applyOpenMonthsFilter($query, $selectedSousCritere);
                break;
            default:
                $query->where('valeur', $selectedSousCritere);
        }
    }
    private function applyMultipleValuesSousCritFilter($query, $selectedSousCritere): void
    {
        if (is_array($selectedSousCritere)) {
            if (isset($selectedSousCritere['id'])) {
                $query->where('sous_critere_valeur_id', $selectedSousCritere['id']);
            } else {
                $query->where(function ($subQuery) use ($selectedSousCritere) {
                    foreach ($selectedSousCritere as $critereSet) {
                        if (is_array($critereSet) && isset($critereSet['id'])) {
                            $subQuery->orWhere('sous_critere_valeur_id', $critereSet['id']);
                        }
                    }
                });
            }
        }


    }
}
