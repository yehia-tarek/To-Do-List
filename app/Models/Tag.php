<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tag_name',
    ];
    public $timestamps = false;

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope);
    }
}
