<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'election_id',
        'name',
        'alias',
        'description',
        'logo',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function canMoveToElection(Election $target): bool
    {
        return $this->election->isDraft() && $target->isDraft();
    }

}
