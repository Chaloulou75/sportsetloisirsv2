<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarAttrValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tar_att_valeurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function attribut(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTartypAttribut::class, 'cat_tar_att_id');
    }

    public function sous_attributs(): HasMany
    {
        return $this->hasMany(LienDisCatTarAttrSousAttr::class, 'att_valeur_id');
    }
}
