<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(TemplatePart::class);
    }
}
