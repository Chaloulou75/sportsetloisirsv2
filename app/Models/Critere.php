<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Critere extends Model
{
    use HasFactory;

    protected $table = 'liste_criteres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function disciplineCategories(): HasMany
    {
        return $this->hasMany(LienDisciplineCategorieCritere::class, 'critere_id');
    }
}
