<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Template extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'total_parts',
        'total_questions',
        'total_score',
        'status'
    ];

    protected $searchableAttributes = [
        'name'
    ];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(TemplatePart::class);
    }
}
