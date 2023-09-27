<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class History extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function parts(): HasMany
    {
        return $this->hasMany(HistoryPart::class, 'history_id');
    }

    public function answers(): HasManyThrough
    {
        return $this->hasManyThrough(HistoryAnswer::class, HistoryPart::class, 'history_id', 'part_id');
    }
}
