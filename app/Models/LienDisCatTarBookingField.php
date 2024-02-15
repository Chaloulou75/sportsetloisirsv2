<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTarBookingField extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tartyp_attributs';

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
}
