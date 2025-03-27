<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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
    // protected static function booted()
    // {
    //     static::creating(function ($book) {
    //         // Get the next auto-increment value for the books table
    //         $nextId = DB::select("SHOW TABLE STATUS LIKE 'books'");
    //         $nextAutoIncrementId = $nextId[0]->Auto_increment;

    //         // Get the maximum value of the book_id column (ignoring the 'B' prefix)
    //         $maxBookId = self::max(DB::raw("CAST(SUBSTRING(book_id, 2) AS UNSIGNED)"));

    //         // Determine the next ID to use, ensuring it's greater than both auto-increment and the highest book_id
    //         $nextIdToUse = max($nextAutoIncrementId, $maxBookId + 1);

    //         // Format the book_id with the 'B' prefix and leading zeros
    //         $book->book_id = 'B' . str_pad($nextIdToUse, 5, '0', STR_PAD_LEFT);
    //     });
    // }



    public function bookMedia()
    {
        return $this->hasMany(BookMedia::class);
    }

    public function bookSize()
    {
        return $this->belongsTo(BookSize::class, 'size');
    }
}
