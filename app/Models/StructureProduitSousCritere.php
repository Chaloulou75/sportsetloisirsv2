<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureProduitSousCritere extends Model
{
    use HasFactory;

    protected $table = 'structure_produit_sous_criteres';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['sous_critere'];

    protected function valeur(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                if (is_string($value)) {
                    if ($this->sous_critere && $this->sous_critere->type_champ_form === 'date') {
                        return Carbon::parse($value)->isoFormat('LL');
                    }
                    if ($this->sous_critere && $this->sous_critere->type_champ_form === 'dates') {
                        $datesArray = json_decode($value, true);
                        if (is_array($datesArray)) {
                            $formattedDates = [];
                            foreach ($datesArray as $date) {
                                $formattedDates[] = Carbon::parse($date)->isoFormat('LL');
                            }
                            return implode(' au ', $formattedDates);
                        }
                    }
                    if ($this->sous_critere && $this->sous_critere->type_champ_form === 'time') {
                        return Carbon::parse($value)->format('H\hi');
                    }
                    if ($this->sous_critere && $this->sous_critere->type_champ_form === 'times') {
                        $timesArray = json_decode($value, true);
                        if (is_array($timesArray)) {
                            $formattedTimes = [];
                            foreach ($timesArray as $time) {
                                $formattedTimes[] = Carbon::parse($time)->format('H\hi');
                            }
                            return implode(' à ', $formattedTimes);
                        }
                    }

                    if ($this->sous_critere && $this->sous_critere->type_champ_form === 'mois') {
                        $monthsArray = json_decode($value, true);
                        if (is_array($monthsArray)) {
                            $formattedMonths = [];
                            foreach ($monthsArray as $month) {
                                $formattedMonths[] = Carbon::parse($month)->isoFormat('MMMM YYYY');
                            }
                            return implode(' à ', $formattedMonths);
                        }
                    }

                    if ($this->sous_critere && $this->sous_critere->type_champ_form === 'range multiple') {
                        try {
                            $decodedValue = json_decode($value, true);
                            if (is_array($decodedValue) && count($decodedValue) === 2) {
                                return "De {$decodedValue[0]} à {$decodedValue[1]} {$this->sous_critere->unite}";
                            }
                            return [];
                        } catch (\Exception $e) {
                            return [];
                        }
                    }

                    return $value;
                }
                return $value;
            }
        );
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function produit_critere(): BelongsTo
    {
        return $this->belongsTo(StructureProduitCritere::class, 'prod_crit_id');
    }

    public function critere(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'critere_id');
    }

    public function critere_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritereValeur::class, 'critere_valeur_id');
    }

    public function sous_critere(): BelongsTo
    {
        return $this->belongsTo(LiensDisCatCritValSsCrit::class, 'sous_critere_id');
    }

    public function sous_critere_valeur(): BelongsTo
    {
        return $this->belongsTo(LiensDisCatCritValSsCritValeur::class, 'sous_critere_valeur_id');
    }
}
