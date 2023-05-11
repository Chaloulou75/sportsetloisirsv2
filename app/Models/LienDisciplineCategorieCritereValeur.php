<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisciplineCategorieCritereValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_disciplines_categories_criteres_valeurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function critere(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'critere_id');
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'categorie_id');
    }
}
