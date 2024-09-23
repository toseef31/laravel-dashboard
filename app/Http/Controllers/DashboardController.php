<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Ephemera;
use App\Models\HardyReel;
use App\Models\User;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $WidgetData = [];
        
        // If no parameters are provided, set flag to fetch all data
        $fetchAll = !$request->hasAny(['users', 'hardy_reels', 'books', 'ephemera']);
    
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
    
            $lastId = HardyReel::max('id');
            $nextId = $lastId ? $lastId + 1 : 1;
            $nextHardyReelId = 'H' . $nextId;
    
            $WidgetData['hardy_reels'] = [
                'count' => $result->hardy_count,
                'total_cost_price' => $result->total_cost_price,
                'total_valuation_price' => $result->total_valuation_price,
                'next_id' => $nextHardyReelId
            ];
        }
    
        // Fetch Books data if requested or if fetching all data
        if ($request->has('books') || $fetchAll) {
            $result = Book::selectRaw('
                SUM(cost_price) as total_cost_price, 
                SUM(valuation) as total_valuation_price,
                COUNT(*) as book_count
            ')->first();
    
            $lastId = Book::max('id');
            $nextId = $lastId ? $lastId + 1 : 1;
            $nextBookId = 'B' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    
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
    
            $lastId = Ephemera::max('id');
            $nextId = $lastId ? $lastId + 1 : 1;
            $nextEphemeraId = 'E' . $nextId;
    
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
