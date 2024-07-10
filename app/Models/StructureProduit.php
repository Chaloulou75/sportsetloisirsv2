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
        return $query->when($filters['criteresBase'] ?? null, function ($query, $criteresBase) {
            foreach ($criteresBase as $critereId => $selectedCritere) {
                if ($this->isEmptyCritere($selectedCritere)) {
                    continue;
                }

                $query->whereHas('criteres', function ($q) use ($critereId, $selectedCritere) {
                    $q->where('critere_id', $critereId);
                    $this->applyFilterBasedOnType($q, $selectedCritere, $critereId);
                });
            }
        })->when($filters['sousCriteres'] ?? null, function ($query, $sousCriteres) {
            foreach ($sousCriteres as $sousCritereId => $selectedSousCritere) {
                if ($selectedSousCritere === null) {
                    continue;
                }

                $query->whereHas('criteres.sousCriteres', function ($q) use ($sousCritereId, $selectedSousCritere) {
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
        $crit = StructureProduitCritere::find($critereId);
        if (!$crit) {
            return null;
        }
        dd($crit->critere->type_champ_form);
        return $crit->critere->type_champ_form;
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
            case 'range multiple':
                $this->applyRangeFilter($query, $selectedCritere);
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

    private function applyRangeFilter($query, $selectedCritere): void
    {
        if (is_array($selectedCritere) && count($selectedCritere) === 2) {
            $query->whereBetween('valeur', $selectedCritere);
        }
    }

    private function applySingleTimeFilter($query, $selectedCritere): void
    {
        $time = Carbon::parse($selectedCritere)->format('H:i');
        $query->whereTime('valeur', $time);
    }

    private function applyOpenTimesFilter($query, $selectedCritere): void
    {
        $times = json_decode($selectedCritere, true);
        if (is_array($times) && count($times) === 2) {
            $startTime = Carbon::parse($times[0])->format('H:i');
            $endTime = Carbon::parse($times[1])->format('H:i');
            $query->whereTime('valeur', '>=', $startTime)
                  ->whereTime('valeur', '<=', $endTime);
        }
    }

    private function applySingleDateFilter($query, $selectedCritere): void
    {
        $date = Carbon::parse($selectedCritere);
        $query->whereDate('valeur', $date);
    }

    private function applyOpenDaysFilter($query, $selectedCritere): void
    {
        $dates = json_decode($selectedCritere, true);
        if (is_array($dates) && count($dates) === 2) {
            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1]);
            $query->whereDate('valeur', '>=', $startDate)
                  ->whereDate('valeur', '<=', $endDate);
        }
    }

    private function applyOpenMonthsFilter($query, $selectedCritere): void
    {
        $months = json_decode($selectedCritere, true);
        if (is_array($months) && count($months) === 2) {
            $startMonth = Carbon::parse($months[0])->startOfMonth();
            $endMonth = Carbon::parse($months[1])->endOfMonth();
            $query->where(function ($subQ) use ($startMonth, $endMonth) {
                $subQ->whereDate('valeur', '>=', $startMonth)
                      ->whereDate('valeur', '<=', $endMonth);
            });
        }
    }

    private function applySousCritereFilter($query, $selectedSousCritere, $sousCritereId): void
    {
        $sousCritere = StructureProduitSousCritere::find($sousCritereId);
        if (!$sousCritere) {
            return;
        }

        $typeChampForm = $sousCritere->sous_critere->type_champ_form;

        switch ($typeChampForm) {
            case 'select':
            case 'checkbox':
            case 'radio':
                $this->applyMultipleValuesFilter($query, $selectedSousCritere);
                break;
            case 'text':
            case 'number':
                $query->where('valeur', $selectedSousCritere);
                break;
            case 'range':
            case 'range multiple':
                $this->applyRangeFilter($query, $selectedSousCritere);
                break;
                // Ajoutez d'autres cas pour les sous-critères si nécessaire
            default:
                $query->where('valeur', $selectedSousCritere);
        }
    }
}
