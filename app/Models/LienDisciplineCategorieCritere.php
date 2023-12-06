<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisciplineCategorieCritere extends Model
{
    use HasFactory;

    protected $table = 'liens_disciplines_categories_criteres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function critere(): BelongsTo
    {
        return $this->belongsTo(Critere::class, 'critere_id');
    }

    public function valeurs(): HasMany
    {
        return $this->hasMany(LienDisciplineCategorieCritereValeur::class, 'discipline_categorie_critere_id');
    }

    /**
     * Scope a query to only include disciplines with products.
     */
    public function scopeWithValeurs(Builder $query): void
    {
        $query->with([
            'valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
            'valeurs.sous_criteres',
            'valeurs.sous_criteres.sous_criteres_valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
        ])->where('visible_front', true)
        ->orderBy('ordre');
    }
}
