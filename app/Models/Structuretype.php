<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Structuretype extends Model
{
    use HasFactory;

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    public function structuretypeattributs(): HasMany
    {
        return $this->hasMany(StructureTypeAttribut::class, 'structuretype_id');

    }
}
