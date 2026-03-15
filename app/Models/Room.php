<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'public',
        'project_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function owner(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Project::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'room_members')
            ->withPivot('role')
            ->withTimestamps();
    }
}
