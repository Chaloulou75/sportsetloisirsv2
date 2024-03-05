<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StructurePlanning extends Model
{
    use HasFactory;

    protected $table = 'structure_produits_planning';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeEndNotPassed(Builder $query): void
    {
        $query->where('end', '>', Carbon::now());
    }

    public function scopeOrderByDateStart(Builder $query): void
    {
        $query->orderBy('start');
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(ProductReservation::class, 'reservation_structure_planning', 'planning_id', 'reservation_id');
    }
}
