<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherReelsOtherTacklesInUseTacklesController extends Controller
{
    public function storeoTHERReelsFromConfig(){
        $REELS = config('REELS_O');
        $REELSArray = [];

        foreach($REELS as $REEL){
            $REELSArray[] = [
                'reel_id' => $REEL['R_ID'],
                'makers_name' => $REEL['Makers_Name'],
                'model' => $REEL['Model'],
                'sub_model' => $REEL['Sub_Model'],
                'size' => $REEL['Size'],
                'foot' => $REEL['Foot'],
                'handle' => $REEL['Handle'],
                'tension_regultor' => $REEL['Tension_Regulator'],
                'details' => $REEL['Details'],
                'approximate_date' => $REEL['Approx_date'],
                'condition' => $REEL['Ocondition'],
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
            DB::table('other_reels')->insert($chunk);
        }
        return response()->json(['message'=> 'Other Reels Inserted Successfully']);
    }

    public function storeOtherTackleFromConfig(){
        $Rodss = config('TACKLE');
        $LuresSArray = [];

        foreach($Rodss as $Lures){
            $LuresSArray[] = [
                'tackle_id' => $Lures['G_ID'],
                'makers_name' => $Lures['Makers_Name'],
                'model' => $Lures['Model'],
                'type'=> $Lures['Type'],
                'sub_model' => $Lures['Sub_Model'],
                'size' => $Lures['Size'],
                'details' => $Lures['Details'],
                'serial_no' => $Lures['Serial'],
                'approximate_date' => $Lures['Approx_date'],
                'condition' => $Lures['Tcondition'],
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
            DB::table('other_tackles')->insert($chunk);
        }
        return response()->json(['message'=> 'tackles Inserted Successfully']);
    }

    public function storeInUseTackleFromConfig(){
        $Rodss = config('TACKLE_U');
        $LuresSArray = [];

        foreach($Rodss as $Lures){
            $LuresSArray[] = [
                'tackle_id' => $Lures['U_ID'],
                'makers_name' => $Lures['Makers_Name'],
                'model' => $Lures['Model'],
                'type'=> $Lures['Type'],
                'sub_model' => $Lures['Sub_Model'],
                'size' => $Lures['Size'],
                'details' => $Lures['Details'],
                'serial_no' => $Lures['SerialNo'],
                'handle' => $Lures['Handle'],
                'transition_regulator' => $Lures['Tension_Regulator'],
                'foot'=> $Lures['Foot'],
                'approximate_date' => $Lures['Approx_date'],
                'condition' => $Lures['Tcondition'],
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
            DB::table('in_use_tackles')->insert($chunk);
        }
        return response()->json(['message'=> 'tackles Inserted Successfully']);
    }
}

