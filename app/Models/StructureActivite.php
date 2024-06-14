<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StructureActivite extends Model
{
    use HasFactory;

    protected $table = 'structures_activites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = [
        'image_url',
        'slug_title'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'actif' => 'boolean',
    ];


    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'activite_id');

    }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'activite_id');

    }

    public function instructeurs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'structure_activite_user')->withPivot('contact', 'email', 'phone');
    }

    public function dates(): HasMany
    {
        return $this->hasMany(StructureActiviteDate::class, 'structure_activite_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ProductReservation::class, 'activite_id');

    }

    /**
     * Get the activite image url.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->image),
        );
    }

    /**
     * Get the slug from the title.
     */
    protected function slugTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::slug($this->titre)
        );
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'structure:id,name,slug,presentation_courte,presentation_longue,address,zip_code,city,country,address_lat,address_lng,user_id,structuretype_id,website,email,facebook,instagram,youtube,tiktok,phone1,phone2,date_creation,view_count,departement_id,logo',
            'structure.creator:id,name',
            'structure.users:id,name',
            'structure.adresses' => function ($query) {
                $query->latest();
            },
            'structure.city:id,ville,ville_formatee,code_postal',
            'structure.departement:id,departement,numero',
            'structure.structuretype:id,name,slug',
            'instructeurs',
            'discipline:id,name,slug',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client,nom_categorie_pro',
            'plannings' => function ($query) {
                $query->endNotPassed();
            },
            'produits' => function ($query) {
                $query->latest();
            },
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.criteres.critere_valeur',
            'produits.criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs.sous_critere_valeur',
            'produits.criteres.sous_criteres',
            'produits.criteres.sous_criteres.sous_critere_valeur',
            'produits.cat_tarifs',
            'produits.cat_tarifs.produits:id',
            'produits.cat_tarifs.categorie',
            'produits.cat_tarifs.cat_tarif_type',
            'produits.cat_tarifs.cat_tarif_type.tarif_attributs',
            'produits.cat_tarifs.cat_tarif_type.tarif_attributs.valeurs',
            'produits.cat_tarifs.cat_tarif_type.tarif_attributs.sous_attributs',
            'produits.cat_tarifs.cat_tarif_type.tarif_attributs.sous_attributs.valeurs',
            'produits.cat_tarifs.attributs',
            'produits.cat_tarifs.attributs.tarif_attribut',
            'produits.cat_tarifs.attributs.tarif_attribut.valeurs',
            'produits.cat_tarifs.attributs.sous_attributs',
            'produits.cat_tarifs.attributs.sous_attributs.sous_attribut',
            'produits.cat_tarifs.attributs.sous_attributs.sous_attribut_valeur',
            'produits.plannings' => function ($query) {
                $query->endNotPassed()->orderByDateStart();
            },
        ]);
    }
}
