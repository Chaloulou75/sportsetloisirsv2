<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LienDisCatTarAttrSousAttr extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tar_att_sous_attributs';

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

    public function valeurs(): HasMany
    {
        return $this->hasMany(LienDisCatTarAttrSousAttrValeur::class, 'sousattribut_id');
    }

}
