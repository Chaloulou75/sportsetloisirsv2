<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureProduitCritere extends Model
{
    use HasFactory;

    protected $table = 'structures_produits_criteres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = ['valeur'];

    protected function valeur(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                if (is_string($value)) {
                    if ($this->critere && $this->critere->type_champ_form === 'date') {
                        return Carbon::parse($value)->isoFormat('LL');
                    }
                    if ($this->critere && $this->critere->type_champ_form === 'dates') {
                        $datesArray = json_decode($value, true);
                        if (is_array($datesArray)) {
                            $formattedDates = [];
                            foreach ($datesArray as $date) {
                                $formattedDates[] = Carbon::parse($date)->isoFormat('LL');
                            }
                            return implode(' au ', $formattedDates);
                        }
                    }
                    if ($this->critere && $this->critere->type_champ_form === 'time') {
                        return Carbon::parse($value)->format('H\hi');
                    }
                    if ($this->critere && $this->critere->type_champ_form === 'times') {
                        $timesArray = json_decode($value, true);
                        if (is_array($timesArray)) {
                            $formattedTimes = [];
                            foreach ($timesArray as $time) {
                                $formattedTimes[] = Carbon::parse($time)->format('H\hi');
                            }
                            return implode(' à ', $formattedTimes);
                        }
                    }

                    if ($this->critere && $this->critere->type_champ_form === 'mois') {
                        $monthsArray = json_decode($value, true);
                        if (is_array($monthsArray)) {
                            $formattedMonths = [];
                            foreach ($monthsArray as $month) {
                                $formattedMonths[] = Carbon::parse($month)->isoFormat('MMMM YYYY');
                            }
                            return implode(' à ', $formattedMonths);
                        }
                    }
                    return $value;
                }
                return $value;
            }
        );
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function critere(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'critere_id');
    }

    public function critere_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritereValeur::class, 'valeur_id');
    }

    public function valeurs(): HasMany
    {
        return $this->hasMany(LienDisciplineCategorieCritereValeur::class, 'discipline_categorie_critere_id');
    }

    public function sous_criteres(): HasMany
    {
        return $this->hasMany(StructureProduitSousCritere::class, 'prod_crit_id');
    }

}
