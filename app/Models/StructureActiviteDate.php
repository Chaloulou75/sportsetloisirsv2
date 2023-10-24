<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureActiviteDate extends Model
{
    use HasFactory;

    protected $table = 'structure_activite_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function structure_activite(): BelongsTo
    {
        return $this->belongsTo(StructureActivite::class);
    }
}
