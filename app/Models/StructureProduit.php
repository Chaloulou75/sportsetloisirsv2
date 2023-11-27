<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class StructureProduit extends Model
{
    use HasFactory;

    protected $table = 'structures_produits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function adresse(): BelongsTo
    {
        return $this->belongsTo(StructureAddress::class, 'lieu_id');
    }

    public function criteres(): HasMany
    {
        return $this->hasMany(StructureProduitCritere::class, 'produit_id');
    }

    public function sous_criteres(): HasMany
    {
        return $this->hasMany(StructureProduitSousCritere::class, 'produit_id');
    }

    public function horaire(): BelongsTo
    {
        return $this->belongsTo(StructureHoraire::class, 'horaire_id');
    }

    public function tarifs(): BelongsToMany
    {
        return $this->belongsToMany(StructureTarif::class, 'produit_tarif', 'produit_id', 'tarif_id');
    }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'produit_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'adresse',
            'discipline:id,name,slug',
            'horaire',
            'categorie',
            'activite:id,titre',
            'structure:id,name,slug',
            'criteres:id,activite_id,produit_id,critere_id,valeur_id,valeur',
            'criteres.critere:id,nom',
            'criteres.critere_valeur.sous_criteres',
            'criteres.critere_valeur.sous_criteres.prodSousCritValeurs.sous_critere_valeur',
            'criteres.sous_criteres',
            'tarifs',
            'tarifs.tarifType',
            'tarifs.structureTarifTypeInfos',
            'plannings',
        ]);
    }

}
