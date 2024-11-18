<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherReel;
use DB;

class OtherReelController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = OtherReel::query();
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

    public function deleteOtherReel(Request $request)
    {
        try {
            $reel = OtherReel::find($request->id);
            if($reel){
                $reel->delete();
                return $this->sendResponse('OtherReel deleted successfully', null, 200);
            }else{
                return $this->sendError('OtherReel not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete reel', $e->getMessage(), 500);
        }
    }

    public function getNextOtherReelId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'other_reels'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxReelId = OtherReel::max(DB::raw("CAST(SUBSTRING(reel_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxReelId + 1);
            $nextOtherReelId = 'R' . $nextIdToUse;
            
            return $this->sendResponse('Next reel ID fetched successfully', ['reel_id' => $nextOtherReelId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next reel ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request){
        try {
            $reel = OtherReel::create($request->all());
            return $this->sendResponse('OtherReel created successfully', $reel, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create reel', $e->getMessage(), 500);
        }
    }
    public function editOtherReel(Request $request){
        try {
            $reel = OtherReel::find($request->id);
            if($reel){
                $reel->update($request->all());
                return $this->sendResponse('OtherReel updated successfully', $reel, 200);
            }else{
                return $this->sendError('OtherReel not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update reel', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $reel = OtherReel::with('reelMedia')->find($id);
            if($reel){
                return $this->sendResponse('OtherReel fetched successfully', $reel, 200);
            }else{
                return $this->sendError('OtherReel not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch reel', $e->getMessage(), 500);
        }
    }
}
