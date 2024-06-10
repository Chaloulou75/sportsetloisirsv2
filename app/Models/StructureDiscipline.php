<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StructureDiscipline extends Pivot
{
    use HasFactory;

    protected $table = 'structures_disciplines';

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

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(StructureCategorie::class, 'discipline_id');
    }

    public function activites(): HasMany
    {
        return $this->hasMany(StructureActivite::class, 'discipline_id');
    }

    public function produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'discipline_id');
    }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'discipline_id');
    }

}
