<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Famille extends Model
{
    use HasFactory;

    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }
}
