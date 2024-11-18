<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HardyReel;
use DB;

class HardyReelController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = HardyReel::query();
            $query->orderBy('id', 'desc');
    
            if ($request->has('reel_id')) {
                $query->where('reel_id', $request->input('reel_id'));
            }
    
            if ($request->has('type')) {
                $query->where('type', 'LIKE', '%' . $request->input('type') . '%');
            }

            if ($request->has('details')) {
                $query->where('details', 'LIKE', '%' . $request->input('details') . '%');
            }
    
            if ($request->has('size')) {
                $query->where('size', 'LIKE', '%' . $request->input('size') . '%');
            }
    
            if ($request->has('cost_price')) {
                $query->where('cost_price', 'LIKE', '%' . $request->input('cost_price') . '%');
            }

            if ($request->has('condition')) {
                $query->where('condition', 'LIKE', '%' . $request->input('condition') . '%');
            }
    
            $query->with('reelMedia');
            $reels = $query->paginate($perPage);
            return $this->sendPaginatedResponse($reels, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch reel(s)', $e->getMessage(), 500);
        }
    }

    public function deleteHardyReel(Request $request)
    {
        try {
            $reel = HardyReel::find($request->id);
            if($reel){
                $reel->delete();
                return $this->sendResponse('HardyReel deleted successfully', null, 200);
            }else{
                return $this->sendError('HardyReel not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete reel', $e->getMessage(), 500);
        }
    }

    public function getNextHardyReelId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'hardy_reels'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxReelId = HardyReel::max(DB::raw("CAST(SUBSTRING(reel_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);
            $nextHardyReelId = 'H' . $nextIdToUse;
            
            return $this->sendResponse('Next reel ID fetched successfully', ['reel_id' => $nextHardyReelId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next reel ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request){
        try {
            $reel = HardyReel::create($request->all());
            return $this->sendResponse('HardyReel created successfully', $reel, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create reel', $e->getMessage(), 500);
        }
    }
    public function editHardyReel(Request $request){
        try {
            $reel = HardyReel::find($request->id);
            if($reel){
                $reel->update($request->all());
                return $this->sendResponse('HardyReel updated successfully', $reel, 200);
            }else{
                return $this->sendError('HardyReel not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update reel', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $reel = HardyReel::with('reelMedia')->find($id);
            if($reel){
                return $this->sendResponse('HardyReel fetched successfully', $reel, 200);
            }else{
                return $this->sendError('HardyReel not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch reel', $e->getMessage(), 500);
        }
    }
}
