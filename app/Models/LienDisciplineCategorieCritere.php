<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'visible_back' => 'boolean',
        'visible_front' => 'boolean',
        'visible_block' => 'boolean',
        'indexable' => 'boolean',
    ];

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

    public function type_champ(): BelongsTo
    {
        return $this->belongsTo(TypeChamp::class, 'type_champ_id');
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
            'type_champ',
            'valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
            'valeurs.sous_criteres',
            'valeurs.sous_criteres.type_champ',
            'valeurs.sous_criteres.sous_criteres_valeurs' => function ($query) {
                $query->orderBy('ordre');
            },
        ])
        ->orderBy('ordre');
    }
}
