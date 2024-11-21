<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'method_name',
        'parameters',
        'status',
        'retries',
        'started_at',
        'completed_at',
        'error_message',
        'priority',
    ];

    protected $casts = [
        'parameters' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'priority' => 'integer',
    ];
}

