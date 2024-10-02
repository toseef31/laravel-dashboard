<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherReelMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'reel_id',
        'media_path',
    ];
}
