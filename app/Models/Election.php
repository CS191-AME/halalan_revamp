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
            'started_at' => 'datetime',
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

    public function isDraft(): bool
    {
        return $this->started_at === null;
    }

    // Get all the IDs of the election and its descendants
    public static function subtreeIds(int $rootId): array
    {
        $ids = [$rootId];
        $frontier = [$rootId];

        while (!empty($frontier)) {
            $frontier = static::whereIn('parent_id', $frontier)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $ids = array_merge($ids, $frontier);
        }

        return $ids;
    }
}