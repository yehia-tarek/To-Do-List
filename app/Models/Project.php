<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name',
        'description',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
