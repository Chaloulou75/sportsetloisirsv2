<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarAttrSousAttrValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tar_att_ssattr_valeurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function sous_attribut(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarAttrSousAttr::class, 'sousattribut_id');
    }
}
