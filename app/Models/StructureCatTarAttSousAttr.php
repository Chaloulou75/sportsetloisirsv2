<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureCatTarAttSousAttr extends Model
{
    use HasFactory;

    protected $table = 'structures_cat_tar_att_ssattr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function cat_tar_attribut(): BelongsTo
    {
        return $this->belongsTo(StructureCatTarAttribut::class, 'str_cat_tar_att_id');
    }

    public function sous_attribut(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarAttrSousAttr::class, 'sousattribut_id');
    }

    public function sous_attribut_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarAttrSousAttrValeur::class, 'ss_att_valeur_id');
    }
}
