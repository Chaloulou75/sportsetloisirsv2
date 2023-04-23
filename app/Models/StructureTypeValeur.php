<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureTypeValeur extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'liste_structures_types_valeurs';

    public function structuretypeattribut(): BelongsTo
    {
        return $this->belongsTo(StructureTypeAttribut::class, 'id', 'id_champ');
    }
}
