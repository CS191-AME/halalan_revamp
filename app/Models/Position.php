<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'max_selections', 
        'can_abstain', 
        'ordinality'
        ];

    protected function casts(): array 
    { 
        return [
            'can_abstain' => 'boolean'
        ]; 
    }

    public function election() 
    { 
        return $this->belongsTo(Election::class); 
    }
}
