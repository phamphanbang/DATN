<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'post',
        'description'
    ];

    protected $searchableAttributes = [
        'name'
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d'
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
