<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Ephemera;
use App\Models\HardyReel;
use App\Models\User;
use App\Models\Lures;
use App\Models\PennCatalogue;
use App\Models\Rods;
use App\Models\OtherReel;
use App\Models\OtherTackle;
use App\Models\InUseTackle;
use DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $WidgetData = [];

        // If no parameters are provided, set flag to fetch all data
        $fetchAll = !$request->hasAny(['users', 'hardy_reels', 'books', 'ephemera', 'lures', 'rods', 'penncatalogue', 'others_reels', 'others_tackle', 'use_tackle']);

        // Fetch Users data if requested or if fetching all data
        if ($request->has('users') || $fetchAll) {
            $users = User::count();
            $WidgetData['users'] = [
                'count' => $users
            ];
        }

        // Fetch Hardy Reels data if requested or if fetching all data
        if ($request->has('hardy_reels') || $fetchAll) {
            $result = HardyReel::selectRaw('
                SUM(cost_price) as total_cost_price, 
                SUM(valuation) as total_valuation_price,
                COUNT(*) as hardy_count
            ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'hardy_reels'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxReelId = HardyReel::max(DB::raw("CAST(SUBSTRING(reel_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);
            $nextHardyReelId = 'H' . $nextIdToUse;

            $WidgetData['hardy_reels'] = [
                'count' => $result->hardy_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextHardyReelId
            ];
        }

        // Fetch Other Reels data if requested or if fetching all data
        if ($request->has('others_reels') || $fetchAll) {
            $result = OtherReel::selectRaw('
                        SUM(cost_price) as total_cost_price, 
                        SUM(valuation) as total_valuation_price,
                        COUNT(*) as other_count
                    ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'other_reels'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxReelId = OtherReel::max(DB::raw("CAST(SUBSTRING(reel_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);
            $nextHardyReelId = 'H' . $nextIdToUse;

            $WidgetData['others_reels'] = [
                'count' => $result->other_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextHardyReelId
            ];
        }

        // Fetch Other TACKLE  data if requested or if fetching all data
        if ($request->has('others_tackle') || $fetchAll) {
            $result = OtherTackle::selectRaw('
                        SUM(cost_price) as total_cost_price, 
                        SUM(valuation) as total_valuation_price,
                        COUNT(*) as tackle_count
                    ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'other_tackles'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxReelId = OtherTackle::max(DB::raw("CAST(SUBSTRING(tackle_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);
            $nextHardyReelId = 'H' . $nextIdToUse;

            $WidgetData['others_tackle'] = [
                'count' => $result->tackle_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextHardyReelId
            ];
        }

        if ($request->has('use_tackle') || $fetchAll) {
            $result = InUseTackle::selectRaw('
                        SUM(cost_price) as total_cost_price, 
                        SUM(valuation) as total_valuation_price,
                        COUNT(*) as use_count
                    ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'in_use_tackles'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxReelId = InUseTackle::max(DB::raw("CAST(SUBSTRING(tackle_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);
            $nextHardyReelId = 'H' . $nextIdToUse;

            $WidgetData['use_tackle'] = [
                'count' => $result->use_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextHardyReelId
            ];
        }

        // Fetch Lures data if requested or if fetching all data
        if ($request->has('lures') || $fetchAll) {
            $result = Lures::selectRaw('
                SUM(cost_price) as total_cost_price, 
                SUM(valuation) as total_valuation_price,
                COUNT(*) as lures_count
            ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'lures'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxLuresId = Lures::max(DB::raw("CAST(SUBSTRING(lures_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxLuresId + 1);
            $nextLuresId = 'L' . str_pad($nextIdToUse, 3, '0', STR_PAD_LEFT);

            $WidgetData['lures'] = [
                'count' => $result->lures_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextLuresId
            ];
        }

        // Fetch Lures data if requested or if fetching all data
        if ($request->has('penncatalogue') || $fetchAll) {
            $result = PennCatalogue::selectRaw('
                        SUM(cost_price) as total_cost_price, 
                        SUM(valuation) as total_valuation_price,
                        COUNT(*) as penncatalogue_count
                    ')->first();


            $WidgetData['penncatalogue'] = [
                'count' => $result->penncatalogue_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
            ];
        }

        // Fetch rods data if requested or if fetching all data
        if ($request->has('rods') || $fetchAll) {
            $result = Rods::selectRaw('
                        SUM(cost_price) as total_cost_price, 
                        SUM(valuation) as total_valuation_price,
                        COUNT(*) as rods_count
                    ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'rods'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum existing rod_id, stripping the 'RD' prefix and casting the remaining number as an unsigned integer
            $maxRodsId = Rods::max(DB::raw("CAST(SUBSTRING(rod_id, 3) AS UNSIGNED)"));

            // If no rod_id exists, set maxRodsId to 0
            $maxRodsId = $maxRodsId ? $maxRodsId : 0;

            // Determine the next ID to use, ensuring it's the maximum of the auto-increment value or the existing rod_id + 1
            $nextIdToUse = max($nextAutoIncrementId, $maxRodsId + 1);

            // Generate the next rod_id, padded to 3 digits
            $nextRodsId = 'RD' . str_pad($nextIdToUse, 3, '0', STR_PAD_LEFT);

            $WidgetData['rods'] = [
                'count' => $result->rods_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextRodsId
            ];
        }

        // Fetch Books data if requested or if fetching all data
        if ($request->has('books') || $fetchAll) {
            $result = Book::selectRaw('
                SUM(cost_price) as total_cost_price, 
                SUM(valuation) as total_valuation_price,
                COUNT(*) as book_count
            ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'books'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum value of the book_id column (ignoring the 'B' prefix)
            $maxBookId = Book::max(DB::raw("CAST(SUBSTRING(book_id, 2) AS UNSIGNED)"));

            // Determine the next ID to be greater than both the next auto-increment and max book_id
            $nextIdToUse = max($nextAutoIncrementId, $maxBookId + 1);

            // Generate the next book_id with 'B' prefix and 5 digits, padded with zeros
            $nextBookId = 'B' . str_pad($nextIdToUse, 5, '0', STR_PAD_LEFT);



            $WidgetData['books'] = [
                'count' => $result->book_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextBookId
            ];
        }

        // Fetch Ephemera data if requested or if fetching all data
        if ($request->has('ephemera') || $fetchAll) {
            $result = Ephemera::selectRaw('
                SUM(cost_price) as total_cost_price, 
                SUM(valuation) as total_valuation_price,
                COUNT(*) as ephemera_count
            ')->first();

            $nextId = DB::select("SHOW TABLE STATUS LIKE 'ephemeras'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum value of the ephemera_id column (ignoring the 'E' prefix)
            $maxEphemeraId = Ephemera::max(DB::raw("CAST(SUBSTRING(ephemera_id, 2) AS UNSIGNED)"));

            // Determine the next ID to use, ensuring it's greater than both auto-increment and the highest ephemera_id
            $nextIdToUse = max($nextAutoIncrementId, $maxEphemeraId + 1);

            // Generate the next ephemera_id with 'E' prefix
            $nextEphemeraId = 'E' . $nextIdToUse;

            $WidgetData['ephemera'] = [
                'count' => $result->ephemera_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextEphemeraId
            ];
        }

        return $this->sendResponse('Dashboard data fetched successfully', $WidgetData, 200);
    }
}
