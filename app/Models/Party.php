<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'election',
        'name',
        'alias',
        'description',
        'logo',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

}
