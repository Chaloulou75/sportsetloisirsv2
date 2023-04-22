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

    public function listactivites(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'liens_activites_categories', 'id_categorie', 'id_activite');
    }

    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'structure_activite_categorie', 'categorie_id', 'activite_id');
    }
}
