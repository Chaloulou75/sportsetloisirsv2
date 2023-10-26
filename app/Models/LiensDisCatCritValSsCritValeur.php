<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiensDisCatCritValSsCritValeur extends Model
{
    use HasFactory;

    protected $table = "liens_dis_cat_crit_val_ss_crit_valeur";

    protected $guarded = [];

    public function sous_critere(): BelongsTo
    {
        return $this->belongsTo(LiensDisCatCritValSsCrit::class, 'dcc_val_ss_crit_id');
    }
}
