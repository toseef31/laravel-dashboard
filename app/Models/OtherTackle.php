<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OtherTackle extends Model
{
    use HasFactory;
    protected $fillable = [
        'tackle_id',
        'makers_name',
        'type',
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
        static::creating(function ($tackle) {
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'other_tackles'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum existing tackle_id, stripping the 'RD' prefix and casting the remaining number as an unsigned integer
            $maxOtherTackleId = OtherTackle::max(DB::raw("CAST(SUBSTRING(tackle_id, 3) AS UNSIGNED)"));

            // If no tackle_id exists, set maxOtherTackleId to 0
            $maxOtherTackleId = $maxOtherTackleId ? $maxOtherTackleId : 0;

            // Determine the next ID to use, ensuring it's the maximum of the auto-increment value or the existing tackle_id + 1
            $nextIdToUse = max($nextAutoIncrementId, $maxOtherTackleId + 1);

            // Generate the next tackle_id, padded to 3 digits
            $tackle->tackle_id = 'G' . str_pad($nextIdToUse, 3, '0', STR_PAD_LEFT);
        });
    }

    public function tackleMedia()
    {
        return $this->hasMany(OtherTackleMedia::class, 'tackle_id', 'id');
    }
}
