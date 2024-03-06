<?php

namespace App\Models;

use App\Models\StructureCatTarif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductReservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paid' => 'boolean',
        'client_confirmed' => 'boolean',
        'client_cancelled' => 'boolean',
        'pending' => 'boolean',
        'confirmed' => 'boolean',
        'finished' => 'boolean',
        'cancelled' => 'boolean',
        'code_confirmed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class, 'activite_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function cat_tarif(): BelongsTo
    {
        return $this->belongsTo(StructureCatTarif::class, 'cat_tarif_id');
    }

    public function attributs(): HasMany
    {
        return $this->hasMany(ReservationAttribut::class, 'reservation_id');
    }

    public function sous_attributs(): HasMany
    {
        return $this->hasMany(ReservationSousAttribut::class, 'reservation_id');
    }

    public function plannings(): BelongsToMany
    {
        return $this->belongsToMany(StructurePlanning::class, 'reservation_structure_planning', 'reservation_id', 'planning_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'user:id,name,email',
            'discipline:id,name',
            'structure:id,name,slug',
            'activite:id,titre',
            'produit',
            'produit.criteres',
            'produit.criteres.critere',
            'produit.criteres.critere_valeur',
            'produit.criteres.critere_valeur.sous_criteres.prodSousCritValeurs.sous_critere_valeur',
            'produit.criteres.sous_criteres',
            'produit.criteres.sous_criteres.sous_critere_valeur',
            'cat_tarif',
            // 'cat_tarif.produits:id',
            'cat_tarif.categorie',
            'cat_tarif.cat_tarif_type',
            'cat_tarif.cat_tarif_type.tarif_attributs',
            'cat_tarif.cat_tarif_type.tarif_attributs.valeurs',
            'cat_tarif.cat_tarif_type.tarif_attributs.sous_attributs',
            'cat_tarif.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
            'cat_tarif.attributs',
            'cat_tarif.attributs.tarif_attribut',
            'cat_tarif.attributs.sous_attributs',
            'cat_tarif.attributs.sous_attributs.sous_attribut',
            'cat_tarif.attributs.sous_attributs.sous_attribut_valeur',
            'plannings',
            'attributs',
            'attributs.reservation_sous_attributs',
        ]);
    }
}
