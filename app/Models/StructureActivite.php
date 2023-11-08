<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
            'dates',
            'instructeurs',
            'discipline:id,name',
            'categorie:id,categorie_id,discipline_id,nom_categorie_client',
            'produits' => function ($query) {
                $query->latest();
            },
            'produits.adresse',
            'produits.criteres',
            'produits.criteres.critere',
            'produits.criteres.critere_valeur.sous_criteres.prodSousCritValeurs',
            'produits.tarifs',
            'produits.tarifs.tarifType',
            'produits.tarifs.structureTarifTypeInfos',
            'produits.tarifs.structureTarifTypeInfos.tarifTypeAttribut',
            'produits.plannings',
        ]);
    }

}
