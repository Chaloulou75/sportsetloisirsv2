<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class StructureProduit extends Model
{
    use HasFactory;

    protected $table = 'structures_produits';

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

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function adresse(): BelongsTo
    {
        return $this->belongsTo(StructureAddress::class, 'lieu_id');
    }

    public function criteres(): HasMany
    {
        return $this->hasMany(StructureProduitCritere::class, 'produit_id');
    }

    // horaire
    // tarif
}
