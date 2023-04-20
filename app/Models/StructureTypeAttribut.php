<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StructureTypeAttribut extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'a_liste_structures_types_attributs';

    public function structuretype(): BelongsTo
    {
        return $this->belongsTo(Structuretype::class, 'a_liste_structures_types_attributs');
    }

    // public function structuretypevaleur(): HasMany
    // {
    //     return $this->hasMany(StructureTypeValeur::class, 'a_liste_structures_types_valeurs');
    // }
}
