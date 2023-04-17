<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorie extends Model
{
    use HasFactory;

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'categorie_discipline', 'categorie_id', 'discipline_id');
    }
}
