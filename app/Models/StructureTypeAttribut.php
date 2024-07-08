<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureTypeAttribut extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'liste_structures_types_attributs';

    public function structuretype(): BelongsTo
    {
        return $this->belongsTo(Structuretype::class, 'structuretype_id');
    }

    public function valeurs(): HasMany
    {
        return $this->hasMany(StructureTypeValeur::class, 'id_champ');
    }
}
