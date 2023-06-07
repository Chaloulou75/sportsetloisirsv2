<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StructureTarif extends Model
{
    use HasFactory;

    protected $table = 'structures_tarifs';

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

    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(StructureProduit::class, 'produit_tarif', 'produit_id', 'tarif_id');
    }

    public function tarifType(): BelongsTo
    {
        return $this->belongsTo(ListeTarifType::class, 'type_id');
    }

    public function structureTarifTypeInfos(): HasMany
    {
        return $this->hasMany(StructureTarifTypeInfo::class, 'tarif_id');
    }

}
