<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * Scope a query to only include familles with products.
     */
    public function scopeWithProducts(Builder $query): void
    {
        $query->withWhereHas('disciplines', function ($subquery) {
            $subquery->whereHas('structureProduits')->select(['liste_disciplines.id', 'liste_disciplines.name', 'liste_disciplines.slug', 'liste_disciplines.theme']);
        })->select(['id', 'name', 'slug']);
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'liens_familles_disciplines', 'famille_id', 'discipline_id');
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

}
