<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Structure extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeFilter($query, array $filters): void
    {
        $query->when(
            $filters['search'] ?? false,
            fn($query, $search) =>
            $query->where(
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('presentation_courte', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('zip_code', 'like', '%' . $search . '%')
            )
            // ->orWhereHas('disciplines', function ($query) use ($search) {
            //     $query->where('name', 'like', '%' . $search . '%')
            //           ->orWhere('slug', 'like', '%' . $search . '%');
            // })
            ->orWhereHas('structuretype', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            })
        );

        // ->orWhereHas('famille', function ($query) use ($search) {
        //         $query->where('name', 'like', '%' . $search . '%')
        //               ->orWhere('slug', 'like', '%' . $search . '%');
        //     })
        // ->orWhereHas('city', function ($query) use ($search) {
        //     $query->where('ville', 'like', '%' . $search . '%')
        //           ->orWhere('code_postal', 'like', '%' . $search . '%');
        // })->orWhereHas('city.department', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // })->orWhereHas('city.department.region', function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // })

        $query->when(
            $filters['famille'] ?? false,
            fn($query, $famille) =>
            $query->whereHas(
                'famille',
                fn($query) =>
                $query->where('slug', $famille)
            )
        );


        $query->when(
            $filters['disciplines'] ?? false,
            fn($query, $disciplines) =>
            $query->whereHas(
                'disciplines',
                fn($query) =>
                $query->where('slug', $disciplines)
            )
        );


        $query->when(
            $filters['localite'] ?? false,
            fn($query, $localite) =>
                $query->where(
                    fn($query) =>
                $query->where('city', 'like', '%' . $localite . '%')
                     ->orWhere('zip_code', 'like', '%' . $localite . '%')
                )
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function partenaires(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'structure_user', 'structure_id', 'user_id')->withPivot('niveau', 'contact', 'email', 'phone');
    }

    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'structure_adresse', 'structure_id', 'city_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'structure_id', 'city_id');
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function structuretype(): BelongsTo
    {
        return $this->belongsTo(Structuretype::class);
    }

    public function adresses(): HasMany
    {
        return $this->hasMany(StructureAddress::class, 'structure_id');
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(StructureDiscipline::class, 'structure_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(StructureCategorie::class, 'structure_id');
    }

    public function activites(): HasMany
    {
        return $this->hasMany(StructureActivite::class, 'structure_id');
    }

    public function produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'structure_id');
    }

    public function tarifs(): HasMany
    {
        return $this->hasMany(StructureTarif::class, 'structure_id');
    }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'structure_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
                'creator:id,name',
                'users:id,name',
                'adresses'  => function ($query) {
                    $query->latest();
                },
                'city:id,ville,ville_formatee,code_postal',
                'departement:id,departement,numero',
                'structuretype:id,name,slug',
                'disciplines',
                'disciplines.discipline:id,name,slug',
                'categories',
                'activites',
                'activites.discipline:id,name',
                'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client,nom_categorie_pro',
                'activites.produits',
                'activites.produits.adresse',
                'activites.produits.criteres',
                'activites.produits.criteres.critere',
                'activites.produits.criteres.critere_valeur.sous_criteres.prodSousCritValeurs',
                'activites.produits.tarifs',
                'activites.produits.tarifs.tarifType',
                'activites.produits.tarifs.structureTarifTypeInfos',
                'activites.produits.plannings',
            ])
            ->select(['id', 'name', 'slug', 'presentation_courte', 'presentation_longue', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng', 'user_id','structuretype_id', 'website', 'email', 'facebook', 'instagram', 'youtube', 'tiktok', 'phone1', 'phone2', 'date_creation', 'view_count', 'departement_id', 'logo']);
    }

}
