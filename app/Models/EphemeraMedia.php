<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EphemeraMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'ephemera_id',
        'media_path'
    ];
}
