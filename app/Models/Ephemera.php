<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EphemeraMedia;
use DB;

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
            // Get the next auto-increment value for the ephemeras table
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'ephemeras'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum value of the ephemera_id column (ignoring the 'E' prefix)
            $maxEphemeraId = self::max(DB::raw("CAST(SUBSTRING(ephemera_id, 2) AS UNSIGNED)"));

            // Determine the next ID to use, ensuring it's greater than both auto-increment and the highest ephemera_id
            $nextIdToUse = max($nextAutoIncrementId, $maxEphemeraId + 1);

            // Generate the new ephemera_id with 'E' prefix
            $ephemera->ephemera_id = 'E' . $nextIdToUse;
        });
    }




    public function ephemeraMedia()
    {
        return $this->hasMany(EphemeraMedia::class);
    }
    public function ephemeraType()
    {
        return $this->hasOne(EphemeraType::class, 'id', 'type');
    }
}
