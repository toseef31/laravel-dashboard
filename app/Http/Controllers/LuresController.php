<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lures;
use DB;

class LuresController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = Lures::query();
            $query->orderBy('id', 'desc');
    
            if ($request->has('lures_id')) {
                $query->where('lures_id', $request->input('lures_id'));
            }
    
            if ($request->has('makers_name')) {
                $query->where('makers_name', 'LIKE', '%' . $request->input('makers_name') . '%');
            }

            if ($request->has('details')) {
                $query->where('details', 'LIKE', '%' . $request->input('details') . '%');
            }
    
            if ($request->has('model')) {
                $query->where('model', 'LIKE', '%' . $request->input('model') . '%');
            }
    
            if ($request->has('sub_model')) {
                $query->where('sub_model', 'LIKE', '%' . $request->input('sub_model') . '%');
            }

            if ($request->has('size')) {
                $query->where('size', 'LIKE', '%' . $request->input('size') . '%');
            }
            if ($request->has('cost_price')) {
                $query->where('cost_price', 'LIKE', '%' . $request->input('cost_price') . '%');
            }
            if ($request->has('valuation')) {
                $query->where('valuation', 'LIKE', '%' . $request->input('valuation') . '%');
            }
    
            $query->with('luresMedia');
            $luress = $query->paginate($perPage);
            return $this->sendPaginatedResponse($luress, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch lures(s)', $e->getMessage(), 500);
        }
    }

    public function deleteLures(Request $request)
    {
        try {
            $lures = Lures::find($request->id);
            if($lures){
                $lures->delete();
                return $this->sendResponse('Lures deleted successfully', null, 200);
            }else{
                return $this->sendError('Lures not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete lures', $e->getMessage(), 500);
        }
    }

    public function getNextLuresId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'lures'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            $maxLuresId = Lures::max(DB::raw("CAST(SUBSTRING(lures_id, 2) AS UNSIGNED)"));
            $nextIdToUse = max($nextAutoIncrementId, $maxLuresId + 1);
            $nextLuresId = 'L' . str_pad($nextIdToUse, 3, '0', STR_PAD_LEFT);
            
            return $this->sendResponse('Next lures ID fetched successfully', ['lures_id' => $nextLuresId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next lures ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request){
        try {
            $lures = Lures::create($request->all());
            return $this->sendResponse('Lures created successfully', $lures, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create lures', $e->getMessage(), 500);
        }
    }
    public function editLures(Request $request){
        try {
            $lures = Lures::find($request->id);
            if($lures){
                $lures->update($request->all());
                return $this->sendResponse('Lures updated successfully', $lures, 200);
            }else{
                return $this->sendError('Lures not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update lures', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $lures = Lures::find($id);
            if($lures){
                return $this->sendResponse('Lures fetched successfully', $lures, 200);
            }else{
                return $this->sendError('Lures not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch lures', $e->getMessage(), 500);
        }
    }
}
