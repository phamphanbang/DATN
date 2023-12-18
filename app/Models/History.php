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

    protected $fillable = [
        'user_id',
        'exam_id',
        'duration',
        'score',
        'test_type',
        'exam_type',
        'right_questions',
        'wrong_questions',
        'total_questions'
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
    ];

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

    public function scopeWhereDateBetween($query,$fieldName,$fromDate,$todate)
    {
        return $query->whereDate($fieldName,'>=',$fromDate)->whereDate($fieldName,'<=',$todate);
    }
}
