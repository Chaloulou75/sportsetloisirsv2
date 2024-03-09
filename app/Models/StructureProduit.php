<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'actif' => 'boolean',
        'reservable' => 'boolean'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['catTarifs'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['minimum_amount'];

    protected function minimumAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->catTarifs->isNotEmpty()
                    ? $this->catTarifs->min('amount')
                    : null;
            }
        );
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

    public function catTarifs(): BelongsToMany
    {
        return $this->belongsToMany(StructureCatTarif::class, 'produit_cat_tarif', 'produit_id', 'cat_tarif_id');
    }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'produit_id');
    }

    public function dates(): HasMany
    {
        return $this->hasMany(StructureActiviteDate::class, 'structure_produit_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'adresse',
            'discipline:id,name,slug,theme',
            'categorie',
            'activite:id,titre',
            'structure:id,name,slug',
            'criteres:id,activite_id,produit_id,critere_id,valeur_id,valeur',
            'criteres.critere',
            'criteres.critere_valeur',
            'criteres.critere_valeur.sous_criteres',
            'criteres.critere_valeur.sous_criteres.prodSousCritValeurs.sous_critere_valeur',
            'criteres.sous_criteres',
            'criteres.sous_criteres.sous_critere_valeur',
            'catTarifs',
            'catTarifs.produits:id',
            'catTarifs.categorie',
            'catTarifs.cat_tarif_type',
            'catTarifs.cat_tarif_type.tarif_attributs',
            'catTarifs.cat_tarif_type.tarif_attributs.valeurs',
            'catTarifs.cat_tarif_type.tarif_attributs.sous_attributs',
            'catTarifs.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
            'catTarifs.cat_tarif_type.tarif_booking_fields',
            'catTarifs.cat_tarif_type.tarif_booking_fields.valeurs',
            'catTarifs.cat_tarif_type.tarif_booking_fields.sous_fields',
            'catTarifs.cat_tarif_type.tarif_booking_fields.sous_fields.valeurs',
            'catTarifs.attributs',
            'catTarifs.attributs.tarif_attribut',
            'catTarifs.attributs.tarif_attribut.valeurs',
            'catTarifs.attributs.sous_attributs',
            'catTarifs.attributs.sous_attributs.sous_attribut',
            'catTarifs.attributs.sous_attributs.sous_attribut_valeur',
            'plannings' => function ($query) {
                $query->endNotPassed()->orderByDateStart();
            },
        ]);
    }

}
