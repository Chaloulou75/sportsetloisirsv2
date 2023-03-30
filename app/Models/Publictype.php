<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publictype extends Model
{
    use HasFactory;

    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }
}
