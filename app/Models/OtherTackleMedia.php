<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherTackleMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'tackle_id',
        'media_path',
    ];
}
