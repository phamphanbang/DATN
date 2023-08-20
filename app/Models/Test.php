<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Test extends Model
{
    use HasFactory;

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(TestPart::class, 'test_id');
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(TestQuestion::class, TestPart::class, 'test_id', 'part_id');
    }

    public function group_questions(): HasManyThrough
    {
        return $this->hasManyThrough(TestGroupQuestion::class, TestPart::class, 'test_id', 'part_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }
}
