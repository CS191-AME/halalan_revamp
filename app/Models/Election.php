<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    /** @use HasFactory<\Database\Factories\ElectionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'ballots_enabled',
    ];

    protected function casts(): array
    {
        return [
            'is_running' => 'boolean',
            'results_available' => 'boolean',
            'ballots_enabled' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Check the status of the election based on its properties
    public function status(): string
    {
        return $this->is_running
            ? 'running'
            : ($this->results_available ? 'ended' : 'draft');
    }

}
