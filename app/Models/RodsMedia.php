<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RodsMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'rod_id',
        'media_path',
    ];
}
