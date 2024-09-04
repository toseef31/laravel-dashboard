<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSize extends Model
{
    use HasFactory;
    protected $fillable = ['size', 'description'];
}
