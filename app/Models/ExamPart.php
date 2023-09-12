<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ExamPart extends Model
{
    use HasFactory;

    protected $table = 'exam_parts';

    protected $fillable = [
        'exam_id',
        'order_in_test',
        'part_type',
        'has_group_question'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(ExamGroup::class, 'part_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class, 'part_id');
    }

    public function question_types(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionType::class, ExamQuestion::class, 'part_id', 'question_type_id');
    }
}
