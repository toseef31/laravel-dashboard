<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class OtherReel extends Model
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
    static::creating(function ($otherreel) {
        // Get the next auto-increment value for the hardy_reels table
        $nextId = DB::select("SHOW TABLE STATUS LIKE 'other_reels'");
        $nextAutoIncrementId = $nextId[0]->Auto_increment;

        // Get the maximum value of the reel_id column (ignoring the 'H' prefix)
        $maxReelId = self::max(DB::raw("CAST(SUBSTRING(reel_id, 2) AS UNSIGNED)"));

        // Determine the next ID to use, ensuring it's greater than both auto-increment and the highest reel_id
        $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);

        // Format the reel_id with the 'H' prefix
        $otherreel->reel_id = 'R' . $nextIdToUse;
    });
}

    

    public function reelMedia()
    {
        return $this->hasMany(OtherReelMedia::class, 'reel_id', 'id');
    }
    
}
