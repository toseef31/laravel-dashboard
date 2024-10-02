<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuresMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'lures_id',
        'media_path',
    ];
}
