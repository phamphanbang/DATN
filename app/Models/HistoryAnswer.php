<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'answer_id',
        'question_id',
        'is_right'
    ];

    protected $table = 'history_answers';

    public function part(): BelongsTo
    {
        return $this->belongsTo(HistoryPart::class, 'part_id');
    }

    // public function answer(): BelongsTo
    // {
    //     return $this->belongsTo(ExamAnswer::class, 'answer_id');
    // }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }
}
