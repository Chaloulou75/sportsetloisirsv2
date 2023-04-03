<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'villes_france';

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
    public function getRouteKeyName(): String
    {
        return 'ville_formatee';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query->where('ville', 'like', '%' . $search . '%')
                    ->orWhere('ville_formatee', 'like', '%' . $search . '%')
                    ->orWhere('code_postal', 'like', '%' . $search . '%')
            )
        );

        $query->when(
            $filters['discipline'] ?? false,
            fn ($query, $discipline) =>
            $query->whereHas(
                'disciplines',
                fn ($query) =>
                $query->where('slug', $discipline)
            )
        );
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'numero', 'departement');
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class, 'city', 'ville');
    }
}
