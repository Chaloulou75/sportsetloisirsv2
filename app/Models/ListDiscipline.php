<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ListDiscipline extends Model
{
    use HasFactory;

    protected $table = 'liste_disciplines';

    protected $guarded = [];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'liens_activites_categories', 'id_activite', 'id_categorie');
    }
}
