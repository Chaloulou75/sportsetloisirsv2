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
        return $this->hasMany(LienDisciplineCategorieCritere::class, 'categorie_id');
    }

    // public function criteres(): BelongsToMany
    // {
    //     return $this->belongsToMany(Critere::class, 'liens_disciplines_categories_criteres', 'categorie_id', 'critere_id')->withPivot('id', 'discipline_id', 'categorie_id', 'critere_id', 'nom', 'type_champ_form', 'ordre', 'visible_back', 'visible_front', 'visible_block', 'indexable');
    // }

    public function tarif_types(): HasMany
    {
        return $this->hasMany(LienDisCatTariftype::class, 'categorie_id');
    }
}
