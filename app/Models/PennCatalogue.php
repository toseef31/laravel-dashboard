<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PennCatalogueMedia;

class PennCatalogue extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'year',
        'catalogue_no',
        'condition',
        'add_date',
        'valuation',
        'cost_price',
        'sold_date',
        'sold_price',
        'buyer_name',
        'buyer_email',
    ];

    public function pennCatalogueMedia()
    {
        return $this->hasMany(PennCatalogueMedia::class, 'catelogue_id', 'id');
    }
}
