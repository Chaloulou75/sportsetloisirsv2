<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienActiviteCategorieCritere extends Model
{
    use HasFactory;

    protected $table = 'liens_activites_categories_criteres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
