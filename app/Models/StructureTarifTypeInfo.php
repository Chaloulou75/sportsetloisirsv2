<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureTarifTypeInfo extends Model
{
    use HasFactory;

    protected $table = 'structures_tarifs_types_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function tarif(): BelongsTo
    {
        return $this->belongsTo(StructureTarif::class, 'tarif_id');
    }

    public function tarifType(): BelongsTo
    {
        return $this->belongsTo(ListeTarifType::class, 'type_id');
    }

    public function tarifTypeAttribut(): BelongsTo
    {
        return $this->belongsTo(ListeTarifTypeAttribut::class, 'attribut_id');
    }
}
