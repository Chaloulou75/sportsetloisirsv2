<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureUser extends Model
{
    use HasFactory;

    protected $table = 'structure_user';

    protected $guarded = [];

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
