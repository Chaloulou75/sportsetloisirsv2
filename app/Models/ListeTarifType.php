<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListeTarifType extends Model
{
    use HasFactory;

    protected $table = 'liste_tarifs_types';

    protected $guarded = [];

    public function tariftypeattributs(): HasMany
    {
        return $this->hasMany(ListeTarifTypeAttribut::class, 'type_id');

    }

    public function tarifs(): HasMany
    {
        return $this->hasMany(StructureTarif::class, 'type_id');

    }

    public function categories(): HasMany
    {
        return $this->hasMany(LienDisCatTariftype::class, 'tarif_type_id');
    }
}
