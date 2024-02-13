<?php

namespace App\Models;

use App\Models\StructureCatTarif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function catTarif(): BelongsTo
    {
        return $this->belongsTo(StructureCatTarif::class, 'tarif_id');
    }

    public function planning(): BelongsTo
    {
        return $this->belongsTo(StructurePlanning::class, 'planning_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'user',
            'produit.criteres',
            'produit.criteres.critere',
            'produit.criteres.critere_valeur',
            'produit.criteres.critere_valeur.sous_criteres.prodSousCritValeurs.sous_critere_valeur',
            'produit.criteres.sous_criteres',
            'produit.criteres.sous_criteres.sous_critere_valeur',
            'produit.activite',
            'catTarif',
            'catTarif.produits:id',
            'catTarif.categorie',
            'catTarif.cat_tarif_type',
            'catTarif.cat_tarif_type.tarif_attributs',
            'catTarif.cat_tarif_type.tarif_attributs.valeurs',
            'catTarif.cat_tarif_type.tarif_attributs.sous_attributs',
            'catTarif.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
            'catTarif.attributs',
            'catTarif.attributs.tarif_attribut',
            'catTarif.attributs.sous_attributs',
            'catTarif.attributs.sous_attributs.sous_attribut',
            'catTarif.attributs.sous_attributs.sous_attribut_valeur',
            'planning'
        ]);
    }
}
