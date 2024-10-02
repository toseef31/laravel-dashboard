<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LuresMedia;
use DB;

class Lures extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'lures_id',
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
    static::creating(function ($lures) {
        $nextId = DB::select("SHOW TABLE STATUS LIKE 'lures'");
        $nextAutoIncrementId = $nextId[0]->Auto_increment;
        $maxLuresId = self::max(DB::raw("CAST(SUBSTRING(lures_id, 2) AS UNSIGNED)"));
        $nextIdToUse = max($nextAutoIncrementId, $maxLuresId + 1);
        $lures->lures_id = 'L' . str_pad($nextIdToUse, 2, '0', STR_PAD_LEFT);
    });
}

    

    public function luresMedia()
    {
        return $this->hasMany(LuresMedia::class, 'lures_id', 'id');
    }
}
