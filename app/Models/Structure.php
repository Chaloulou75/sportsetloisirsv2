<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Structure extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeFilter($query, array $filters): void
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('presentation_courte', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('zip_code', 'like', '%' . $search . '%')
            )->orWhereHas('activites.discipline', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            })->orWhereHas('structuretype', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            })
        );

        // ->orWhereHas('famille', function ($query) use ($search) {
        //         $query->where('name', 'like', '%' . $search . '%')
        //               ->orWhere('slug', 'like', '%' . $search . '%');
        //     })
        // ->orWhereHas('city', function ($query) use ($search) {
        //     $query->where('ville', 'like', '%' . $search . '%')
        //           ->orWhere('code_postal', 'like', '%' . $search . '%');
        // })->orWhereHas('city.department', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // })->orWhereHas('city.department.region', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // })

        $query->when(
            $filters['famille'] ?? false,
            fn ($query, $famille) =>
            $query->whereHas(
                'famille',
                fn ($query) =>
                $query->where('slug', $famille)
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


        $query->when(
            $filters['localite'] ?? false,
            fn ($query, $localite) =>
                $query->where(
                    fn ($query) =>
                $query->where('city', 'like', '%' . $localite . '%')
                     ->orWhere('zip_code', 'like', '%' . $localite . '%')
                )
        );

    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'structure_villes_france', 'structure_id', 'villes_france_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'code_postal', 'zip_code');
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function structuretype(): BelongsTo
    {
        return $this->belongsTo(Structuretype::class);
    }

    public function discipline(): HasManyThrough
    {
        return $this->hasManyThrough(Discipline::class, Activite::class);
    }

    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class);
    }
}
