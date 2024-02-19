<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarBookingFieldSsFieldValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_d_c_t_booking_field_ss_field_valeurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function booking_sous_field(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingFieldSousField::class, 'sousfield_id');
    }
}
