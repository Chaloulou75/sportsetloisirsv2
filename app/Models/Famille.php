<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Famille extends Model
{
    use HasFactory;

    protected $table = 'liste_familles';

    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'liens_familles_disciplines', 'famille_id', 'discipline_id');
    }

    // public function structures(): HasMany
    // {
    //     return $this->hasMany(Structure::class);
    // }

    public function structures(): HasManyThrough
    {
        return $this->hasManyThrough(
            Structure::class,
            ListDiscipline::class,
            'famille_id',
            'id',
            'id',
            'id'
        );
    }
}
