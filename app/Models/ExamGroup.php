<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamGroup extends Model
{
    use HasFactory;

    protected $table = 'exam_groups';

    protected $fillable = [
        'part_id',
        'order_in_part',
        'question',
        'attachment',
        'from_question',
        'to_question',
        'audio'
    ];

    public function part():BelongsTo
    {
        return $this->belongsTo(ExamPart::class,'part_id')->orderBy('order_in_part');
    }

    public function questions():HasMany
    {
        return $this->hasMany(ExamQuestion::class,'group_id');
    }
}
