<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HardyReel extends Model
{
    use HasFactory;

    protected $fillable = [
        'reel_id',
        'makers_name',
        'model',
        'sub_model',
        'size',
        'approximate_date',
        'foot',
        'handle',
        'tension_regultor',
        'details',
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
        static::creating(function ($hardyreel) {
            // Get the maximum 'id' from the 'books' table
            $lastId = self::max('id');
            
            // Calculate the next ID
            $nextId = $lastId ? $lastId + 1 : 1;
            
            // Format the book_id with the 'B' prefix and leading zeros
            $hardyreel->reel_id = 'H' . $nextId;
        });
    }
    

    public function reelMedia()
    {
        return $this->hasMany(HardyReelMedia::class, 'reel_id', 'id');
    }
    
}
