<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TestPart extends Model
{
    use HasFactory;

    protected $table = 'test_parts';

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id')->orderBy('order_in_test');
    }

    public function group_questions(): HasMany
    {
        return $this->hasMany(TestGroupQuestion::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(TestQuestion::class, 'part_id');
    }

    public function question_types(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionType::class, TestQuestion::class, 'part_id', 'question_type_id');
    }
}
