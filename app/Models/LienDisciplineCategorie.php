<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LienDisciplineCategorie extends Model
{
    use HasFactory;

    protected $table = 'liens_disciplines_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function structures_activites(): HasMany
    {
        return $this->hasMany(StructureActivite::class, 'categorie_id');
    }

    public function structures_produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'categorie_id');
    }

    public function criteres(): HasMany
    {
        return $this->hasMany(LienDisciplineCategorieCritere::class, 'categorie_id', 'categorie_id')
                ->where('discipline_id', $this->discipline_id);
    }
}
