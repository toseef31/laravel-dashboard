<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Lures;
use App\Models\PennCatalogue;
use App\Models\Rods;
use Illuminate\Http\Request;
use DB;

class LuresRodsPennController extends Controller
{
    public function storeLuresFromConfig(){
        $Luress = config('LURES');
        $LuresSArray = [];

        foreach($Luress as $Lures){
            $LuresSArray[] = [
                'lures_id' => $Lures['L_ID'],
                'makers_name' => $Lures['Makers_Name'],
                'model' => $Lures['Model'],
                'sub_model' => $Lures['Sub_Model'],
                'size' => $Lures['Size'],
                'details' => $Lures['Details'],
                'serial_no' => $Lures['Serial'],
                'approximate_date' => $Lures['Approx_date'],
                'condition' => $Lures['Lcondition'],
                'add_date' => $Lures['Date_Purchased'],
                'valuation' => $Lures['Valuation'],
                'cost_price' => $Lures['Price_paid'],
                'sold_date' => $Lures['date_sold'],
                'sold_price' => $Lures['sale_price'],
                'buyer_name' => $Lures['sold_to'],
                'buyer_email' => $Lures['sold_to_email'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $chunks = array_chunk($LuresSArray, 50);
        foreach($chunks as $chunk){
            DB::table('lures')->insert($chunk);
        }
        return response()->json(['message'=> 'lures Inserted Successfully']);
    }

    public function storeRodsFromConfig(){
        $Rodss = config('RODS');
        $LuresSArray = [];

        foreach($Rodss as $Lures){
            $LuresSArray[] = [
                'rod_id' => $Lures['RD_ID'],
                'makers_name' => $Lures['Makers_Name'],
                'model' => $Lures['Model'],
                'sub_model' => $Lures['Sub_Model'],
                'size' => $Lures['Size'],
                'details' => $Lures['Details'],
                'serial_no' => $Lures['Serial'],
                'approximate_date' => $Lures['Approx_date'],
                'condition' => $Lures['Rcondition'],
                'add_date' => $Lures['Date_Purchased'],
                'valuation' => $Lures['Valuation'],
                'cost_price' => $Lures['Price_paid'],
                'sold_date' => $Lures['date_sold'],
                'sold_price' => $Lures['sale_price'],
                'buyer_name' => $Lures['sold_to'],
                'buyer_email' => $Lures['sold_to_email'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $chunks = array_chunk($LuresSArray, 50);
        foreach($chunks as $chunk){
            DB::table('rods')->insert($chunk);
        }
        return response()->json(['message'=> 'rods Inserted Successfully']);
    }

    public function storePennFromConfig(){
        $Rodss = config('PENN');
        $LuresSArray = [];

        foreach($Rodss as $Lures){
            $LuresSArray[] = [
                'name' => $Lures['PN_ID'],
                'year' => $Lures['Year'],
                'catalogue_no' => $Lures['Cat'],
                'condition' => $Lures['Pcondition'],
                'add_date' => $Lures['date_added'],
                'valuation' => $Lures['Valuation'],
                'cost_price' => $Lures['cost'],
                'sold_date' => $Lures['date_sold'],
                'sold_price' => $Lures['sale_price'],
                'buyer_name' => $Lures['sold_to'],
                'buyer_email' => $Lures['sold_to_email'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $chunks = array_chunk($LuresSArray, 50);
        foreach($chunks as $chunk){
            DB::table('penn_catalogues')->insert($chunk);
        }
        return response()->json(['message'=> 'Penns Inserted Successfully']);
    }

    
    public function insertMediaLures(){
        $files = config('files');
        $bookImages = [];
        foreach ($files as $file) {
            if($file['TableName'] == 'LURES'){
                $ephemera = Lures::where('lures_id', $file['ID'])->first();
                if($ephemera){
                    $ephemeraImages[] = [
                        'lures_id' => $ephemera->id,
                        'media_path' => 'uploads/'.basename($file['FilePath']),
                    ];
                }
            }
        }
        $chunks = array_chunk($ephemeraImages, 10);
        foreach ($chunks as $chunk) {
            DB::table('lures_media')->insert($chunk);
        }
        return response()->json('Inserted Successfully', 200);
    }

    public function insertMediaRods(){
        $files = config('files');
        $ephemeraImages = [];
        foreach ($files as $file) {
            if($file['TableName'] == 'RODS'){
                $ephemera = Rods::where('rod_id', $file['ID'])->first();
                if($ephemera){
                    $ephemeraImages[] = [
                        'rod_id' => $ephemera->id,
                        'media_path' => 'uploads/'.basename($file['FilePath']),
                    ];
                }
            }
        }
        $chunks = array_chunk($ephemeraImages, 10);
        foreach ($chunks as $chunk) {
            DB::table('rods_media')->insert($chunk);
        }
        return response()->json('Inserted Successfully', 200);
    }
    public function insertMediaPenn(){
        $files = config('files');
        $ephemeraImages = [];
        foreach ($files as $file) {
            if($file['TableName'] == 'PENN'){
                $ephemera = PennCatalogue::where('name', $file['ID'])->first();
                if($ephemera){
                    $ephemeraImages[] = [
                        'catelogue_id' => $ephemera->id,
                        'media_path' => 'uploads/'.basename($file['FilePath']),
                    ];
                }
            }
        }
        $chunks = array_chunk($ephemeraImages, 10);
        foreach ($chunks as $chunk) {
            DB::table('penn_catalogue_media')->insert($chunk);
        }
        return response()->json('Inserted Successfully', 200);
    }
}
