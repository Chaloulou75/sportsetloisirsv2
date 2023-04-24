<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Discipline extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
            )
        );
    }

    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class);
    }

    public function structures(): BelongsToMany
    {
        return $this->belongsToMany(Structure::class, 'structure_activite', 'structure_id', 'activite_id');
    }

    // public function structures()
    // {
    //     return $this->hasManyThrough(
    //         Structure::class,
    //         Activite::class,
    //         'discipline_id',
    //         'id',
    //         'id',
    //         'structure_id'
    //     );
    // }

    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'categorie_discipline', 'discipline_id', 'categorie_id');
    }
}
