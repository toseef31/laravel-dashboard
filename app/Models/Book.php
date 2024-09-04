<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'title',
        'author',
        'description',
        'pages',
        'size',
        'edition',
        'publisher',
        'publication_year',
        'status',
        'book_condition',
        'jacket_condition',
        'comment',
        'add_date',
        'sold_date',
        'buyer_name',
        'buyer_email',
        'sold_price',
        'cost_price',
        'valuation',
    ];
}
