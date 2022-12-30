<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RandomTest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
}
