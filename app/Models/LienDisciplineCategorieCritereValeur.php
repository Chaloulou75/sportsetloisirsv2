<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LienDisciplineCategorieCritereValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_disciplines_categories_criteres_valeurs';

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
        'defaut' => 'boolean',
        'inclus_all' => 'boolean'
    ];

    public function critere(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'discipline_categorie_critere_id');
    }

    public function sous_criteres(): HasMany
    {
        return $this->hasMany(LiensDisCatCritValSsCrit::class, 'dis_cat_crit_val_id');
    }

    public function produit_sous_criteres(): HasMany
    {
        return $this->hasMany(StructureProduitSousCritere::class, 'critere_valeur_id');
    }
}
