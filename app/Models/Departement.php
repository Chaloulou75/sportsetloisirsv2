<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Departement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    // /**
    //  * Get the route key for the model.
    //  *
    //  * @return string
    //  */
    // public function getRouteKeyName()
    // {
    //     return 'numero';
    // }

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

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    public function activites(): HasMany
    {
        return $this->hasMany(Activite::class);
    }
}
