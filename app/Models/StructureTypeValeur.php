<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StructureTypeValeur extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'a_liste_structures_types_valeurs';

    // public function structuretype(): BelongsTo
    // {
    //     return $this->belongsTo(Structuretype::class, 'a_liste_structures_types_attributs');
    // }
}
