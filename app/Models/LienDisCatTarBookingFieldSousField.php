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

    public function booking_field_valeur(): BelongsTo
    {
        return $this->belongsTo(LienDisCatTarBookingFieldValeur::class, 'field_valeur_id');
    }

    public function type_champ(): BelongsTo
    {
        return $this->belongsTo(TypeChamp::class, 'type_champ_id');
    }

    public function valeurs(): HasMany
    {
        return $this->hasMany(LienDisCatTarBookingFieldSsFieldValeur::class, 'sousfield_id');
    }
}
