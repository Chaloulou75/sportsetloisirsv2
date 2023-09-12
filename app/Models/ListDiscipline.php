<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function familles(): BelongsToMany
    {
        return $this->belongsToMany(Famille::class, 'liens_familles_disciplines', 'discipline_id', 'famille_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'liens_disciplines_categories', 'discipline_id', 'categorie_id')->withPivot('nom_categorie_pro', 'nom_categorie_client');
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
}
