<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pixel extends Model
{
    public function canvas(): BelongsTo
    {
        return $this->belongsTo(Canvas::class);
    }
}
