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


                // if (is_string($value)) {
                //     // Check if it's a JSON-encoded string
                //     $decodedValue = json_decode($value, true);

                //     if ($decodedValue !== null) {
                //         return $decodedValue;
                //     }

                //     // Check if it's a valid date or time
                //     $dateValue = Carbon::parse($value, 'UTC', false);


                //     if ($dateValue->isValid()) {
                //         if ($dateValue->format('H:i:s') === '00:00:00') {
                //             return $dateValue->isoFormat('LL'); // Format date for humans (e.g., "23 mars 2024")
                //         } else {
                //             return $dateValue->format('H\hi'); // Format time for humans (e.g., "9h00")
                //         }
                //     }


                //     // Handle other cases as needed
                //     return $value;
                // } elseif (is_array($value)) {
                //     // Handle array cases as needed
                //     // Example: JSON encode the array
                //     return json_encode($value);
                // }

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
