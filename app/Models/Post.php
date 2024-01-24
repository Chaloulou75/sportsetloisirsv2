<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'image_url'
    ];

    /**
     * Get the route key for the model.
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
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%')
                    ->orWhereHas('tags', fn($query) => $query->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('disciplines', fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            )
        );

        $query->when(
            $filters['author'] ?? false,
            fn($query, $author) =>
            $query->whereHas(
                'author',
                fn($query) =>
                $query->where('name', 'like', '%' . $author . '%')
            )
        );
    }

    public function scopeOrderByDiscipline(Builder $query, $disciplineId)
    {
        $query->with(['author', 'comments', 'tags', 'disciplines'])
            ->whereHas('disciplines', function ($query) use ($disciplineId) {
                $query->where('discipline_post.discipline_id', $disciplineId);
            })
            ->withCount('comments')
            ->latest();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'discipline_post', 'post_id', 'discipline_id');
    }

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    /**
     * Get the post image.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->thumbnail),
        );
    }
}
