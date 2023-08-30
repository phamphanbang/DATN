<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplatePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_in_test',
        'total_questions',
        'has_group_question',
        'part_type',
        'template_id'
    ];

    protected $table = 'template_parts';

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
