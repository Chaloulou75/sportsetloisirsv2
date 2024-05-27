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
        'paiement_datetime' => 'datetime',
        'client_confirmed' => 'boolean',
        'datetime_client_confirmed' => 'datetime',
        'client_cancelled' => 'boolean',
        'datetime_client_cancelled' => 'datetime',
        'pending' => 'boolean',
        'confirmed' => 'boolean',
        'datetime_structure_confirmed' => 'datetime',
        'finished' => 'boolean',
        'datetime_structure_finished' => 'datetime',
        'cancelled' => 'boolean',
        'datetime_structure_cancelled' => 'datetime',
        'code_confirmed' => 'boolean',
        'datetime_code_confirmed' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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
        return $this->belongsToMany(StructurePlanning::class, 'reservation_structure_planning', 'reservation_id', 'planning_id')
        ->withPivot('quantity');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'user:id,name,email',
            'customer',
            'discipline:id,name,slug',
            'structure:id,name,slug',
            'activite:id,titre,description',
            'produit',
            'produit.adresse',
            'produit.criteres',
            'produit.criteres.critere',
            'produit.criteres.critere_valeur',
            'produit.criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs.sous_critere_valeur',
            'produit.criteres.sous_criteres',
            'produit.criteres.sous_criteres.sous_critere_valeur',
            'cat_tarif',
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
            'attributs.booking_field',
            'attributs.reservation_sous_attributs',
            'attributs.reservation_sous_attributs.booking_sous_field'
        ]);
    }
}
