<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Departement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): String
    {
        return 'slug';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false,
            fn($query, $search) =>
            $query->where(
                fn($query) =>
                $query->where('departement', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('numero', 'like', '%' . $search . '%')
            )
        );
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'departement', 'numero');
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    public function scopeWithCitiesAndRelations(Builder $query): void
    {
        $query->with([
            'cities' => function ($q) {
                $q->whereHas('produits');
            },
            'cities.produits.structure:id,name',
            'cities.produits.adresse',
            'cities.produits.activite:id,titre',
            'cities.produits.criteres:id,activite_id,produit_id,critere_id,valeur_id,valeur',
            'cities.produits.criteres.critere:id,nom',
            'cities.produits.criteres.critere_valeur.sous_criteres.prodSousCritValeurs',
            'cities.produits.criteres.sous_criteres',
            'cities.produits.tarifs',
            'cities.produits.tarifs.tarifType',
            'cities.produits.tarifs.structureTarifTypeInfos',
            'cities.produits.plannings',
        ]);
    }

}
