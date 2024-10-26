<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
