<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    public function publictype(): BelongsTo
    {
        return $this->belongsTo(Publictype::class);
    }

    public function activitetype(): BelongsTo
    {
        return $this->belongsTo(Activitetype::class);
    }

    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(ListDiscipline::class);
    }

    public function structures(): BelongsToMany
    {
        return $this->belongsToMany(Structure::class, 'structure_activite', 'structure_id', 'activite_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'structure_activite_categorie', 'activite_id', 'categorie_id');
    }
}
