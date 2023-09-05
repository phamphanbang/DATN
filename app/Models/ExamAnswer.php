<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $table = 'exam_answers';

    public function question(): BelongsTo
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id')->orderBy('order_in_question');
    }

    public function history_answers(): HasMany
    {
        return $this->hasMany(HistoryAnswer::class, 'answer_id');
    }
}
