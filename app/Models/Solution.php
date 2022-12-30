<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solution extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kata(): BelongsTo
    {
        return $this->belongsTo(Kata::class);
    }

    public function lang(): BelongsTo
    {
        return $this->belongsTo(Lang::class);
    }

    /**
     * replace ">" to "&lt;" to avoid cutting the code inside <pre> tag
     *
     * @return Attribute
     */
    //protected function body(): Attribute
    //{
    //    return Attribute::make(
    //        get: fn ($code) => htmlspecialchars($code),
    //    );
    //}
}
