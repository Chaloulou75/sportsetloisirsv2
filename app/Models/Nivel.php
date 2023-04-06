<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nivel extends Model
{
    use HasFactory;

    public function activite(): HasOne
    {
        return $this->hasOne(Activite::class);
    }
}
