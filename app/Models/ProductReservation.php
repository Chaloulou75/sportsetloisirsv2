<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(StructureProduit::class, 'produit_id');
    }

    public function tarif(): BelongsTo
    {
        return $this->belongsTo(StructureTarif::class, 'tarif_id');
    }

    public function planning(): BelongsTo
    {
        return $this->belongsTo(StructurePlanning::class, 'planning_id');
    }
}
