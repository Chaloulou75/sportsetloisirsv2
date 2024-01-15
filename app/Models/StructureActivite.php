<?php

namespace App\Models;

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
        'image_url'
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
        return $this->belongsToMany(User::class)->withPivot('contact', 'email', 'phone');
    }

    public function dates(): HasMany
    {
        return $this->hasMany(StructureActiviteDate::class, 'structure_activite_id');
    }

    /**
     * Get the activite image url.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->image),
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
            'plannings',
            'produits' => function ($query) {
                $query->latest();
            },
            'produits.adresse',
            'produits.dates',
            'produits.horaire',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.criteres.critere_valeur',
            'produits.criteres.sous_criteres',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
            // 'produits.minimumAmount',
            'produits.catTarifs',
            'produits.catTarifs.attributs',
            'produits.catTarifs.attributs.tarif_attribut',
            'produits.catTarifs.attributs.sous_attributs',
            'produits.catTarifs.attributs.sous_attributs.sous_attribut_valeur',
            'produits.plannings',
        ]);
    }

}
