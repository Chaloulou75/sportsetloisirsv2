<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StructureActiviteProduitDeclinaisonCritere extends Model
{
    use HasFactory;

    protected $table = 'structure_activite_produit_declinaison_critere';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
