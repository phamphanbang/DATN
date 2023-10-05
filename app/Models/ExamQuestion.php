<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $table = 'exam_questions';

    protected $fillable = [
        'part_id',
        'group_id',
        'order_in_test',
        'question',
        'attachment',
        'audio'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ExamGroup::class, 'group_id')->orderBy('order_in_test');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(ExamPart::class, 'part_id')->orderBy('order_in_test');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'question_id');
    }

    public function history_answers(): HasMany
    {
        return $this->hasMany(HistoryAnswer::class, 'question_id');
    }
}
