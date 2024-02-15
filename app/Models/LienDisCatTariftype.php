<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LienDisCatTarBookingField;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisCatTariftype extends Model
{
    use HasFactory;

    protected $table = 'liens_dis_cat_tariftypes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(LienDisciplineCategorie::class, 'categorie_id');
    }

    public function tarif_type(): BelongsTo
    {
        return $this->belongsTo(ListeTarifType::class, 'tarif_type_id');
    }

    public function tarif_attributs(): HasMany
    {
        return $this->hasMany(LienDisCatTartypAttribut::class, 'cat_tarif_id');
    }

    public function tarif_booking_fields(): HasMany
    {
        return $this->hasMany(LienDisCatTarBookingField::class, 'cat_tarif_id');
    }
}
