<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EphemeraMedia;

class Ephemera extends Model
{
    use HasFactory;
    protected $fillable = [
        'ephemera_id',
        'type',
        'details',
        'size',
        'approximate_date',
        'condition',
        'add_date',
        'valuation',
        'cost_price',
        'sold_date',
        'sold_price',
        'buyer_name',
        'buyer_email',
    ];

    protected static function booted()
    {
        static::creating(function ($ephemera) {
            // Get the maximum 'id' from the 'books' table
            $lastId = self::max('id');
            
            // Calculate the next ID
            $nextId = $lastId ? $lastId + 1 : 1;
            
            // Format the book_id with the 'B' prefix and leading zeros
            $ephemera->ephemera_id = 'E' . $nextId;
        });
    }
    


    public function ephemeraMedia()
    {
        return $this->hasMany(EphemeraMedia::class);
    }


}
