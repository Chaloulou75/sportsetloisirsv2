<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StructureCatTarif extends Model
{
    use HasFactory;

    protected $table = 'structures_cat_tarifs';

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

    public function cat_tarif_type(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTariftype::class, 'dis_cat_tar_typ_id');
    }

    public function attributs(): HasMany
    {
        return $this->hasMany(StructureCatTarAttribut::class, 'str_cat_tar_id');
    }

    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(StructureProduit::class, 'produit_cat_tarif', 'cat_tarif_id', 'produit_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'categorie',
            'categorie.discipline:id,name,slug',
            'cat_tarif_type',
            'cat_tarif_type.tarif_attributs',
            'cat_tarif_type.tarif_attributs.valeurs',
            'cat_tarif_type.tarif_attributs.sous_attributs',
            'cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
            'attributs',
            'attributs.tarif_attribut',
            'attributs.tarif_attribut.valeurs',
            'attributs.sous_attributs',
            'attributs.sous_attributs.sous_attribut',
            'attributs.sous_attributs.sous_attribut_valeur',
        ]);
    }
}
