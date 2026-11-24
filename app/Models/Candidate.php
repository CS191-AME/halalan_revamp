<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    // The candidates table has no created_at / updated_at columns
    public $timestamps = false;

    // Columns that create() and update() are allowed to fill
    protected $fillable = [
        'first_name',
        'last_name',
        'alias',
        'party_id',
        'position_id',
        'description',
        'picture',
    ];

    // Each candidate runs for one position
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    // party_id can be null (independent), so this can return null
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
