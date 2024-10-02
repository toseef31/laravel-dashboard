<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\RodsMedia;

class Rods extends Model
{
    use HasFactory;
    protected $fillable = [
        'rode_id',
        'makers_name',
        'model',
        'sub_model',
        'size',
        'approximate_date',
        'serial_no',
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
        static::creating(function ($rods) {
$nextId = DB::select("SHOW TABLE STATUS LIKE 'rods'");
$nextAutoIncrementId = $nextId[0]->Auto_increment;

// Get the maximum existing rod_id, stripping the 'RD' prefix and casting the remaining number as an unsigned integer
$maxRodsId = Rods::max(DB::raw("CAST(SUBSTRING(rod_id, 3) AS UNSIGNED)"));

// If no rod_id exists, set maxRodsId to 0
$maxRodsId = $maxRodsId ? $maxRodsId : 0;

// Determine the next ID to use, ensuring it's the maximum of the auto-increment value or the existing rod_id + 1
$nextIdToUse = max($nextAutoIncrementId, $maxRodsId + 1);

// Generate the next rod_id, padded to 3 digits
$rods->rod_id = 'RD' . str_pad($nextIdToUse, 3, '0', STR_PAD_LEFT);
        });
    }

    

    public function rodsMedia()
    {
        return $this->hasMany(RodsMedia::class, 'rod_id', 'id');
    }
}
