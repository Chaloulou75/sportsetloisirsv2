<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureTypeInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'structures_types_infos';

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function attribut(): BelongsTo
    {
        return $this->belongsTo(StructureTypeAttribut::class, 'attribut_id');
    }

    public function attribut_valeur(): BelongsTo
    {
        return $this->belongsTo(StructureTypeValeur::class, 'valeur_id');
    }

}
