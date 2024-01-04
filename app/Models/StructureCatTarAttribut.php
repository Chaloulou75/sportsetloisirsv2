<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StructureCatTarAttribut extends Model
{
    use HasFactory;

    protected $table = 'structures_cat_tar_attributs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function cat_tarif(): BelongsTo
    {
        return $this->belongsTo(StructureCatTarif::class, 'str_cat_tar_id');
    }

    public function tarif_attribut(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTartypAttribut::class, 'cat_tar_att_id');
    }

    public function sous_attributs(): HasMany
    {
        return $this->hasMany(StructureCatTarAttSousAttr::class, 'str_cat_tar_att_id');
    }
}
