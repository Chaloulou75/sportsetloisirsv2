<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('zip_code', 'like', '%' . $search . '%')
            )->orWhereHas('disciplines', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
        );

        // ->orWhereHas('city', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%')
        //           ->orWhere('zip_code', 'like', '%' . $search . '%');
        // })->orWhereHas('city.department', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // })->orWhereHas('city.department.region', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // })

        $query->when(
            $filters['category'] ?? false,
            fn ($query, $category) =>
            $query->whereHas(
                'category',
                fn ($query) =>
                $query->where('slug', $category)
            )
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function structuretype(): BelongsTo
    {
        return $this->belongsTo(Structuretype::class);
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(Discipline::class);
    }
}
