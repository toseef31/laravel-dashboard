<?php

namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Ephemera;
use App\Models\HardyReel;

class HardyAndEphemeraController extends Controller
{
    public function storeEphemeraFromConfig(){

        $ephemeras = config('EPHEMERA');
        $ephemeraArray = [];

        foreach($ephemeras as $ephemera){
            $ephemeraArray[] = [
                'ephemera_id' => $ephemera['E_ID'],
                'type' => $ephemera['Type'],
                'details' => $ephemera['Details'],
                'size' => $ephemera['Sizewh'],
                'approximate_date' => $ephemera['Appdate'],
                'condition' => $ephemera['Econdition'],
                'add_date' => $ephemera['date_added'],
                'valuation' => $ephemera['Valuation'],
                'cost_price' => $ephemera['Price'],
                'sold_date' => $ephemera['date_sold'],
                'sold_price' => $ephemera['sale_price'],
                'buyer_name' => $ephemera['sold_to'],
                'buyer_email' => $ephemera['sold_to_email'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $chunks = array_chunk($ephemeraArray, 50);
        foreach($chunks as $chunk){
            DB::table('ephemeras')->insert($chunk);
        }
        return response()->json(['message'=> 'Ephemeras Inserted Successfully']);
    }
    public function storeHardyReelsFromConfig(){
        $REELS = config('REELS_H');
        $REELSArray = [];

        foreach($REELS as $REEL){
            $REELSArray[] = [
                'reel_id' => $REEL['H_ID'],
                'makers_name' => $REEL['Makers_Name'],
                'model' => $REEL['Model'],
                'sub_model' => $REEL['Sub_Model'],
                'size' => $REEL['Size'],
                'foot' => $REEL['Foot'],
                'handle' => $REEL['Handle'],
                'tension_regultor' => $REEL['Tension_Regulator'],
                'details' => $REEL['Details'],
                'approximate_date' => $REEL['Approx_date'],
                'condition' => $REEL['Hcondition'],
                'add_date' => $REEL['Date_Purchased'],
                'valuation' => $REEL['Valuation'],
                'cost_price' => $REEL['Price_paid'],
                'sold_date' => $REEL['date_sold'],
                'sold_price' => $REEL['sale_price'],
                'buyer_name' => $REEL['sold_to'],
                'buyer_email' => $REEL['sold_to_email'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $chunks = array_chunk($REELSArray, 50);
        foreach($chunks as $chunk){
            DB::table('hardy_reels')->insert($chunk);
        }
        return response()->json(['message'=> 'Hardy Reels Inserted Successfully']);
    }

    public function insertMediaEphemera(){
        $files = config('files');
        $bookImages = [];
        foreach ($files as $file) {
            if($file['TableName'] == 'EPHEMERA'){
                $ephemera = Ephemera::where('ephemera_id', $file['ID'])->first();
                if($ephemera){
                    $ephemeraImages[] = [
                        'ephemera_id' => $ephemera->id,
                        'media_path' => 'uploads/'.basename($file['FilePath']),
                    ];
                }
            }
        }
        $chunks = array_chunk($ephemeraImages, 10);
        foreach ($chunks as $chunk) {
            DB::table('ephemera_media')->insert($chunk);
        }
        return response()->json('Inserted Successfully', 200);
    }
    public function insertMediaHardy(){
        $files = config('files');
        $reelsImages = [];
        foreach ($files as $file) {
            if($file['TableName'] == 'REELS_H'){
                $reel = HardyReel::where('reel_id', $file['ID'])->first();
                if($reel){
                    $reelsImages[] = [
                        'reel_id' => $reel->id,
                        'media_path' => 'uploads/'.basename($file['FilePath']),
                    ];
                }
            }
        }
        $chunks = array_chunk($reelsImages, 10);
        foreach ($chunks as $chunk) {
            DB::table('hardy_reel_media')->insert($chunk);
        }
        return response()->json('Inserted Successfully', 200);
    }
}
