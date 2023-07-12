<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LienDisciplineSimilaire extends Model
{
    use HasFactory;

    protected $table = 'liens_disciplines_similaires';

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

    public function disciplinesSimilaires(): BelongsTo
    {
        return $this->belongsTo(ListDiscipline::class, 'discipline_similaire_id');
    }

}
