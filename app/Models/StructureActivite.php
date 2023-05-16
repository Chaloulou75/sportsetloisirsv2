<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class StructureActivite extends Model
{
    use HasFactory;

    protected $table = 'structures_activites';

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

    public function structures(): BelongsToMany
    {
        return $this->belongsToMany(Structure::class, 'structures_produits', 'activite_id', 'structure_id')->withTimestamps();
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(LienDisciplineCategorie::class, 'structures_produits', 'activite_id', 'categorie_id')->withTimestamps();
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'structures_produits', 'activite_id', 'discipline_id')->withTimestamps();
    }

    public function produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'activite_id');

    }

}
