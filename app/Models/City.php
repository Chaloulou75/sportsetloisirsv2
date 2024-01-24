<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        return 'slug';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false,
            fn($query, $search) =>
            $query->where(
                fn($query) =>
                $query->where('ville', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('ville_formatee', 'like', '%' . $search . '%')
                    ->orWhere('code_postal', 'like', '%' . $search . '%')
            )
        );

        $query->when(
            $filters['discipline'] ?? false,
            fn($query, $discipline) =>
            $query->whereHas(
                'disciplines',
                fn($query) =>
                $query->where('slug', $discipline)
            )
        );
    }

    /**
     * Scope a query to only include cities with products.
     */
    public function scopeWithProducts(Builder $query): void
    {
        $query->whereHas('produits')
            ->with('city_departement:id,slug,departement,numero,view_count')
            ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon', 'departement']);
    }

    /**
     * Scope a query to only include cities with products.
     */
    public function scopeWithProductsAndDepartement(Builder $query): void
    {
        $query->with(['produits', 'city_departement:id,slug,departement,numero,view_count'])
            ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon', 'departement']);
    }

    public function scopeWithCitiesAround(Builder $query, $city, $limit = 10)
    {
        $query->whereHas('produits')
            ->select(['id', 'slug', 'code_postal', 'ville', 'ville_formatee', 'nom_departement', 'departement', 'view_count', 'latitude', 'longitude', 'tolerance_rayon'])
            ->selectRaw("
                (6366 * acos(
                    cos(radians({$city->latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$city->longitude})) +
                    sin(radians({$city->latitude})) * sin(radians(latitude))
                )) AS distance
            ")
            ->whereNot('slug', $city->slug)
            ->havingRaw('distance <= ?', [$city->tolerance_rayon])
            ->orderBy('distance', 'ASC')
            ->limit($limit);
    }

    protected function radians($degrees)
    {
        return $degrees * pi() / 180;
    }

    public function city_departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departement', 'numero');
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    public function adresses(): HasMany
    {
        return $this->hasMany(StructureAddress::class, 'city_id');
    }

    public function produits(): HasManyThrough
    {
        return $this->hasManyThrough(
            StructureProduit::class,
            StructureAddress::class,
            'city_id',
            'lieu_id',
            'id',
            'id'
        );
    }

}
