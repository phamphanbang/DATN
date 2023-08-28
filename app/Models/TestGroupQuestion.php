<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestGroupQuestion extends Model
{
    use HasFactory;

    protected $table = 'test_group_questions';

    public function part():BelongsTo
    {
        return $this->belongsTo(TestPart::class,'part_id')->orderBy('order_in_part');
    }

    public function questions():HasMany
    {
        return $this->hasMany(TestQuestion::class,'group_id');
    }
}
