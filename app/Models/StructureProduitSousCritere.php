<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureProduitSousCritere extends Model
{
    use HasFactory;

    protected $table = 'structure_produit_sous_criteres';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function produit_critere(): BelongsTo
    {
        return $this->belongsTo(StructureProduitCritere::class, 'prod_crit_id');
    }

    public function critere(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritere::class, 'critere_id');
    }

    public function critere_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorieCritereValeur::class, 'critere_valeur_id');
    }

    public function sous_critere(): BelongsTo
    {
        return $this->belongsTo(LiensDisCatCritValSsCrit::class, 'sous_critere_id');
    }

    public function sous_critere_valeur(): BelongsTo
    {
        return $this->belongsTo(LiensDisCatCritValSsCritValeur::class, 'sous_critere_valeur_id');
    }
}
