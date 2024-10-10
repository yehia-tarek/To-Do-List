<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_name',
        'file_type',
        'file_size',
        'file_url',
        'uploaded_at',
    ];
}
