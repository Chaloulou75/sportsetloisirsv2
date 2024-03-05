<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationAttribut extends Model
{
    use HasFactory;

    protected $table = 'reservation_attributs';

    protected $guarded = [];

    public function booking_field(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingField::class, 'booking_field_id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(ProductReservation::class, 'reservation_id');
    }

    public function reservation_sous_attributs(): HasMany
    {
        return $this->hasMany(ReservationSousAttribut::class, 'reservation_attribut_id');
    }
}
