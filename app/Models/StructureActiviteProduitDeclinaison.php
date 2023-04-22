<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StructureActiviteProduitDeclinaison extends Model
{
    use HasFactory;

    protected $table = 'structure_activite_produit_declinaison';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
