<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarBookingFieldValeur extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tar_booking_field_valeurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function booking_field(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingField::class, 'cat_tar_field_id');
    }

    public function sous_fields(): HasMany
    {
        return $this->hasMany(LienDisCatTarBookingFieldSousField::class, 'field_valeur_id');
    }
}
