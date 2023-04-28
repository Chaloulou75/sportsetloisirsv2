<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsToMany(ListDiscipline::class, 'liens_disciplines_categories', 'categorie_id', 'discipline_id');
    }

    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(StructureActivite::class, 'structures_produits', 'categorie_id', 'activite_id');
    }
}
