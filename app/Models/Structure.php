<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Structure extends Model
{
    use HasFactory;
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    protected $appends = [
        'image_url'
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->logo),
        );
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('presentation_courte', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('zip_code', 'like', '%' . $search . '%')
            )
            ->orWhereHas('structuretype', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            })
        );

        $query->when(
            $filters['famille'] ?? false,
            fn ($query, $famille) =>
            $query->whereHas(
                'famille',
                fn ($query) =>
                $query->where('slug', $famille)
            )
        );


        $query->when(
            $filters['disciplines'] ?? false,
            fn ($query, $disciplines) =>
            $query->whereHas(
                'disciplines',
                fn ($query) =>
                $query->where('slug', $disciplines)
            )
        );


        $query->when(
            $filters['localite'] ?? false,
            fn ($query, $localite) =>
                $query->where(
                    fn ($query) =>
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
        return $this->belongsToMany(User::class, 'structure_user', 'structure_id', 'user_id')->withPivot('niveau', 'contact', 'email', 'phone');
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'structure_adresse', 'structure_id', 'city_id')->withPivot('name', 'email', 'phone', 'address', 'zip_code', 'city', 'country', 'address_lat', 'address_lng');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
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

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'structures_disciplines', 'structure_id', 'discipline_id')->using(StructureDiscipline::class)->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(LienDisciplineCategorie::class, 'structures_categories', 'structure_id', 'categorie_id')->using(StructureCategorie::class)->withTimestamps();
    }

    public function activites(): HasMany
    {
        return $this->hasMany(StructureActivite::class, 'structure_id');
    }

    public function produits(): HasMany
    {
        return $this->hasMany(StructureProduit::class, 'structure_id');
    }

    // public function tarifs(): HasMany
    // {
    //     return $this->hasMany(StructureTarif::class, 'structure_id');
    // }

    public function plannings(): HasMany
    {
        return $this->hasMany(StructurePlanning::class, 'structure_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ProductReservation::class, 'structure_id');
    }

    public function scopeWithRelations(Builder $query): void
    {
        $query->with([
            'creator:id,name',
            'users:id,name',
            'adresses'  => function ($query) {
                $query->latest();
            },
            'departement:id,departement,numero',
            'structuretype:id,name,slug',
            'disciplines:id,name,slug,theme',
            'disciplines.str_categories' => function ($query) {
                $query->withCount('str_activites');
            },
            'disciplines.str_categories.str_activites',
            'categories',
            'activites',
            'activites.discipline:id,name,slug,theme',
            'activites.categorie:id,categorie_id,discipline_id,nom_categorie_client,nom_categorie_pro',
            'activites.produits',
            'activites.produits.adresse',
            'activites.produits.criteres',
            'activites.produits.criteres.critere',
            'activites.produits.criteres.sous_criteres',
            'activites.produits.criteres.critere_valeur',
            'activites.produits.plannings',
        ])->withCount([
            'disciplines',
            'activites',
        ]);
    }

}
