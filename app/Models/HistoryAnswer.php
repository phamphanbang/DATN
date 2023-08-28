<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryAnswer extends Model
{
    use HasFactory;

    protected $table = 'history_answers';

    public function part(): BelongsTo
    {
        return $this->belongsTo(HistoryPart::class, 'part_id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(TestAnswer::class, 'answer_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(TestQuestion::class, 'question_id');
    }
}
