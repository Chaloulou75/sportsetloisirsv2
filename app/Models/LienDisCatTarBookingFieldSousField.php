<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarBookingFieldSousField extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tar_book_field_ss_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function booking_field(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingField::class, 'booking_field_id');
    }

    public function valeurs(): HasMany
    {
        return $this->hasMany(LienDisCatTarBookingFieldSsFieldValeur::class, 'sousfield_id');
    }
}
