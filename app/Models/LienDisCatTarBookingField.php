<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarBookingField extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tar_booking_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function cat_tarif_type(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarifType::class, 'cat_tarif_id');
    }

    public function valeurs(): HasMany
    {
        return $this->hasMany(LienDisCatTarBookingFieldValeur::class, 'cat_tar_field_id');
    }

    public function sous_fields(): HasMany
    {
        return $this->hasMany(LienDisCatTarBookingFieldSousField::class, 'booking_field_id');
    }

    public function reservation_attributs(): HasMany
    {
        return $this->hasMany(ReservationAttribut::class, 'booking_field_id');
    }
}
