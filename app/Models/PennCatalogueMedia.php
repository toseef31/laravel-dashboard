<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PennCatalogueMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalogue_id',
        'media_path',
    ];
}
