<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienDisciplineCategorieCritereValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_disciplines_categories_criteres_valeurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
