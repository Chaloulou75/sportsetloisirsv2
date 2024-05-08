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
        return $this->belongsToMany(ListDiscipline::class, 'liens_disciplines_categories', 'categorie_id', 'discipline_id')->withPivot('id', 'slug', 'nom_categorie_pro', 'nom_categorie_client');
    }

    // public function criteres(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         Critere::class, // Target model
    //         LienDisciplineCategorieCritere::class,        // Intermediate model
    //         'categorie_id',                         // Foreign key on intermediate model
    //         'categorie_id',                         // Foreign key on target model
    //         'id',                                   // Local key on current model
    //         'id'                                    // Local key on intermediate model
    //     );
    // }

    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(StructureActivite::class, 'structures_produits', 'categorie_id', 'activite_id');
    }

}
