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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($book) {
            // Set the book_id before the book is created
            $lastBook = self::latest()->first();
            $nextId = $lastBook ? $lastBook->id + 1 : 1;
            $book->book_id = 'B' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }


    public function bookMedia()
    {
        return $this->hasMany(BookMedia::class);
    }

    public function bookSize()
    {
        return $this->belongsTo(BookSize::class, 'size');
    }
}
