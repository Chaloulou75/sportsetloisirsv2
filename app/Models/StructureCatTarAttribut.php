<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureCatTarAttribut extends Model
{
    use HasFactory;

    protected $table = 'structures_cat_tar_attributs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function cat_tarif(): BelongsTo
    {
        return $this->belongsTo(StructureCatTarif::class, 'str_cat_tar_id');
    }
}
