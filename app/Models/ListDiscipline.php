<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ListDiscipline extends Model
{
    use HasFactory;

    protected $table = 'liste_disciplines';

    protected $guarded = [];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    /**
     * Scope a query to only include disciplines with products.
     */
    public function scopeWithProducts(Builder $query): void
    {
        $query->whereHas('structureProduits')->select(['id', 'name', 'slug', 'theme', 'view_count']);
    }

    /**
     * Scope a query to include disciplines with products and disciplines similaires.
     */
    public function scopeWithProductsAndDisciplinesSimilaires(Builder $query): void
    {
        $query->with([
            'structureProduits',
            'disciplinesSimilaires' => function ($query) {
                $query->select('discipline_similaire_id', 'name', 'slug', 'famille', 'theme', 'view_count');
            }
        ])->select(['id', 'name', 'slug', 'view_count', 'theme']);
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when(
            $filters['search'] ?? false,
            fn($query, $search) =>
            $query->where(
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
            )
        );
    }

    public function familles(): BelongsToMany
    {
        return $this->belongsToMany(Famille::class, 'liens_familles_disciplines', 'discipline_id', 'famille_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'liens_disciplines_categories', 'discipline_id', 'categorie_id')->withPivot('slug', 'nom_categorie_pro', 'nom_categorie_client');
    }

    public function structures(): BelongsToMany
    {
        return $this->belongsToMany(Structure::class, 'structures_disciplines', 'discipline_id', 'structure_id');
    }

    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(StructureActivite::class, 'structures_produits', 'discipline_id', 'activite_id');
    }

    public function structureProduits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'discipline_id');
    }

    public function disciplinesSimilaires(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'liens_disciplines_similaires', 'discipline_id', 'discipline_similaire_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'discipline_post', 'discipline_id', 'post_id');
    }
}
