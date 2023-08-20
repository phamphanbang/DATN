<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TestQuestion extends Model
{
    use HasFactory;

    protected $table = 'test_questions';

    public function group_question(): BelongsTo
    {
        return $this->belongsTo(TestGroupQuestion::class, 'group_id')->orderBy('order_in_test');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(TestPart::class, 'part_id')->orderBy('order_in_test');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class,'question_id');
    }

    public function right_answer(): BelongsTo
    {
        return $this->belongsTo(TestAnswer::class);
    }

    public function question_type(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    public function history_answers(): HasMany
    {
        return $this->hasMany(HistoryAnswer::class, 'question_id');
    }
}
