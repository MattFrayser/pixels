<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Canvas extends Model
{
    protected $fillable = [
        'room',
        'width',
        'height',
        'public'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function pixel(): HasMany
    {
        return $this->hasMany(Pixel::class);
    }
}
