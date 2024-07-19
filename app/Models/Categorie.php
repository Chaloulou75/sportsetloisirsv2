<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Categorie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'liens_disciplines_categories', 'categorie_id', 'discipline_id')->using(LienDisciplineCategorie::class)->withPivot('id', 'slug', 'nom_categorie_pro', 'nom_categorie_client')->withTimestamps();
    }

    public function disc_categories(): HasMany
    {
        return $this->hasMany(LienDisciplineCategorie::class, 'categorie_id', 'id');
    }

    public function criteres(): BelongsToMany
    {
        return $this->belongsToMany(
            Critere::class,
            'liens_disciplines_categories_criteres',
            'categorie_id',
            'critere_id'
        )->using(LienDisciplineCategorieCritere::class)->withPivot('id', 'discipline_id', 'categorie_id', 'critere_id', 'nom', 'type_champ_form', 'type_champ_id', 'ordre', 'visible_back', 'visible_front', 'visible_block', 'indexable')->withTimestamps();
    }

    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(StructureActivite::class, 'structures_produits', 'categorie_id', 'activite_id');
    }

}
