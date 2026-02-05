<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'type',
        'content',
        'file_path',
        'file_name',
        'max_views',
        'current_views',
        'expires_at',
        'sender'
    ];
}
