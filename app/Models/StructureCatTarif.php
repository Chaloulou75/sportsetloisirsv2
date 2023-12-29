<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StructureCatTarif extends Model
{
    use HasFactory;

    protected $table = 'structures_cat_tarifs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function cat_tarif_type(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTariftype::class, 'dis_cat_tar_typ_id');
    }

    public function attributs(): HasMany
    {
        return $this->hasMany(StructureCatTarAttribut::class, 'str_cat_tar_id');
    }
}
