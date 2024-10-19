<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('user', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
