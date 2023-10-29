<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function critere(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'discipline_categorie_critere_id');
    }

    public function sous_criteres(): HasMany
    {
        return $this->hasMany(LiensDisCatCritValSsCrit::class, 'dis_cat_crit_val_id');
    }
}
