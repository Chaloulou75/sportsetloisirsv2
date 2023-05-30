<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
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
