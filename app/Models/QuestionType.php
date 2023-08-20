<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionType extends Model
{
    use HasFactory;

    protected $table = 'question_types';

    public function questions(): HasMany
    {
        return $this->hasMany(TestQuestion::class, 'question_type_id');
    }
}
