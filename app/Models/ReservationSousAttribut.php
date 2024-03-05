<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationSousAttribut extends Model
{
    use HasFactory;

    protected $table = 'reservation_sous_attributs';

    protected $guarded = [];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(ProductReservation::class, 'reservation_id');
    }

    public function reservation_attribut(): BelongsTo
    {
        return $this->belongsTo(ReservationAttribut::class, 'reservation_attribut_id');
    }

    public function booking_sous_field(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingFieldSousField::class, 'booking_field_ss_field_id');
    }

    public function booking_sous_field_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingFieldSsFieldValeur::class, 'booking_ss_field_valeur_id');
    }
}
