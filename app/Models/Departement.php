<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Departement extends Model
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
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'numero';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query->where('departement', 'like', '%' . $search . '%')
                    ->orWhere('numero', 'like', '%' . $search . '%')
            )
        );
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'departement', 'numero');
    }

    public function structures(): BelongsToMany
    {
        return $this->belongsToMany(Structure::class);
    }
}