<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListeTarifTypeAttribut extends Model
{
    use HasFactory;

    protected $table = 'liste_tarifs_types_attributs';

    protected $guarded = [];

    public function tarifType(): BelongsTo
    {
        return $this->belongsTo(ListeTarifType::class, 'type_id');
    }
}
