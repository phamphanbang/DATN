<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Exam extends Model
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
        return $this->hasMany(ExamPart::class, 'exam_id');
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(ExamQuestion::class, ExamPart::class, 'exam_id', 'part_id');
    }

    public function group_questions(): HasManyThrough
    {
        return $this->hasManyThrough(ExamGroup::class, ExamPart::class, 'exam_id', 'part_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }
}
