<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
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
            'structures:id,name,slug,city_id,departement_id',
            'cities' => function ($q) {
                $q->whereHas('produits');
            },
            'cities.produits',
            'cities.produits.structure:id,name,slug',
            'cities.produits.adresse',
            'cities.produits.activite:id,titre',
            'cities.produits.criteres:id,activite_id,produit_id,critere_id,valeur_id,valeur',
            'cities.produits.criteres.critere',
            'cities.produits.criteres.critere_valeur.sous_criteres.prod_sous_crit_valeurs',
            'cities.produits.criteres.sous_criteres',
            'cities.produits.catTarifs',
            'cities.produits.catTarifs.attributs',
            'cities.produits.catTarifs.attributs.tarif_attribut',
            'cities.produits.catTarifs.attributs.sous_attributs',
            'cities.produits.catTarifs.attributs.sous_attributs.sous_attribut',
            'cities.produits.catTarifs.attributs.sous_attributs.sous_attribut_valeur',
            'cities.produits.plannings',
        ]);
    }

}
