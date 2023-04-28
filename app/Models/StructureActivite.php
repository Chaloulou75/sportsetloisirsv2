<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class, 'structures_produits', 'activite_id', 'discipline_id')->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'structures_produits', 'activite_id', 'categorie_id')->withTimestamps();
    }

    public function structures(): BelongsToMany
    {
        return $this->belongsToMany(Structure::class, 'structures_produits', 'activite_id', 'structure_id')->withTimestamps();
    }
}
