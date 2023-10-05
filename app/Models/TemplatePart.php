<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplatePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_in_test',
        'num_of_questions',
        'has_group_question',
        'part_type',
        'template_id',
        'num_of_answers'
    ];

    protected $table = 'template_parts';

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(ExamPart::class,'template_part_id');
    }
}
