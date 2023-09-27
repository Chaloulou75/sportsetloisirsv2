<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureAddress extends Model
{
    use HasFactory;

    protected $table = 'structure_adresse';

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

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function departement()
    {
        return $this->hasManyThrough(
            Departement::class,
            City::class,
            'departement',
            'numero',
            'city_id',
            'id'
        );
    }

    public function produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'lieu_id');
    }
}
