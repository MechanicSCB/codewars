<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Kata extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $incrementing = false;
    public $keyType = 'string';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kata) {
            $kata->slug = $kata->name;
        });
    }

    public function langs(): BelongsToMany
    {
        return $this->belongsToMany(Lang::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault([
            'name' => 'no-user',
        ]);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault([
            'name' => 'no-user',
        ]);
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    public function sample(): HasOne
    {
        return $this->hasOne(Sample::class);
    }

    public function random_test(): HasOne
    {
        return $this->hasOne(RandomTest::class);
    }

    public static function generateId(): string
    {
        $generatedId = dechex(time());

        for ($i = 0; $i < 16; $i++) {
            $generatedId .= dechex(rand(0, 15));
        }

        return $generatedId;
    }

    public function setSlugAttribute(string $value)
    {
        $slug = $original = Str::slug($value);
        $count = 2;

        /** @noinspection PhpUndefinedMethodInspection */
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        $this->attributes['slug'] = $slug;
    }
}
