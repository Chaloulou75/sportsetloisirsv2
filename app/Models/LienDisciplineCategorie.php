<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LienDisciplineCategorie extends Pivot
{
    use HasFactory;

    protected $table = 'liens_disciplines_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function str_categories(): HasMany
    {
        return $this->hasMany(StructureCategorie::class, 'categorie_id');
    }

    public function str_activites(): HasMany
    {
        return $this->hasMany(StructureActivite::class, 'categorie_id');
    }

    public function structures_produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'categorie_id');
    }

    public function criteres(): HasMany
    {
        return $this->hasMany(LienDisciplineCategorieCritere::class, 'categorie_id');
    }

    public function tarif_types(): HasMany
    {
        return $this->hasMany(LienDisCatTariftype::class, 'categorie_id');
    }
}
