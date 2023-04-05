<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activite extends Model
{
    use HasFactory;

    public function nivels(): HasOne
    {
        return $this->hasOne(Nivel::class);
    }

    public function publictypes(): HasOne
    {
        return $this->hasOne(Nivel::class);
    }

    public function activitetype(): HasOne
    {
        return $this->hasOne(Activitetype::class);
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }
}
