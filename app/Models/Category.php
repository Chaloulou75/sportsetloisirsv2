<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class);
    }
}
