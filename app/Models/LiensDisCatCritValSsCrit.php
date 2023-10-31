<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiensDisCatCritValSsCrit extends Model
{
    use HasFactory;

    protected $table = "liens_dis_cat_crit_val_sous_criteres";

    protected $guarded = [];

    public function critere_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritereValeur::class, 'dis_cat_crit_val_id');
    }

    public function sous_criteres_valeurs(): HasMany
    {
        return $this->hasMany(LiensDisCatCritValSsCritValeur::class, 'dcc_val_ss_crit_id');

    }

    public function prodSousCritValeurs(): HasMany
    {
        return $this->hasMany(StructureProduitSousCritere::class, 'sous_critere_id');
    }

}
