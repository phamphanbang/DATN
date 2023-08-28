<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HistoryPart extends Model
{
    use HasFactory;

    protected $table = 'history_parts';

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class, 'history_id');
    }

    public function test_part(): BelongsTo
    {
        return $this->belongsTo(TestPart::class, 'part_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(HistoryAnswer::class, 'part_id');
    }
}
