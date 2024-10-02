<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InUseTackle;
use DB;

class InUseTackleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = InUseTackle::query();
            $query->orderBy('id', 'desc');
    
            if ($request->has('tackle_id')) {
                $query->where('tackle_id', $request->input('tackle_id'));
            }
    
            if ($request->has('makers_name')) {
                $query->where('makers_name', 'LIKE', '%' . $request->input('makers_name') . '%');
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
            if ($request->has('sold_price')) {
                $query->where('sold_price', 'LIKE', '%' . $request->input('sold_price') . '%');
            }
    
            $query->with('tackleMedia');
            $tackles = $query->paginate($perPage);
            return $this->sendPaginatedResponse($tackles, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch tackle(s)', $e->getMessage(), 500);
        }
    }

    public function deleteInUseTackle(Request $request)
    {
        try {
            $tackle = InUseTackle::find($request->id);
            if($tackle){
                $tackle->delete();
                return $this->sendResponse('InUseTackle deleted successfully', null, 200);
            }else{
                return $this->sendError('InUseTackle not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete tackle', $e->getMessage(), 500);
        }
    }

    public function getNextInUseTackleId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'in_use_tackles'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;
            
            // Get the maximum existing tackle_id, stripping the 'RD' prefix and casting the remaining number as an unsigned integer
            $maxInUseTackleId = InUseTackle::max(DB::raw("CAST(SUBSTRING(tackle_id, 3) AS UNSIGNED)"));
            
            // If no tackle_id exists, set maxInUseTackleId to 0
            $maxInUseTackleId = $maxInUseTackleId ? $maxInUseTackleId : 0;
            
            // Determine the next ID to use, ensuring it's the maximum of the auto-increment value or the existing tackle_id + 1
            $nextIdToUse = max($nextAutoIncrementId, $maxInUseTackleId + 1);
            
            // Generate the next tackle_id, padded to 3 digits
            $nextInUseTackleId = 'U' . str_pad($nextIdToUse, 3, '0', STR_PAD_LEFT);
            
            return $this->sendResponse('Next tackle ID fetched successfully', ['tackle_id' => $nextInUseTackleId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next tackle ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request){
        try {
            $tackle = InUseTackle::create($request->all());
            return $this->sendResponse('InUseTackle created successfully', $tackle, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create tackle', $e->getMessage(), 500);
        }
    }
    public function editInUseTackle(Request $request){
        try {
            $tackle = InUseTackle::find($request->id);
            if($tackle){
                $tackle->update($request->all());
                return $this->sendResponse('InUseTackle updated successfully', $tackle, 200);
            }else{
                return $this->sendError('InUseTackle not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update tackle', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $tackle = InUseTackle::find($id);
            if($tackle){
                return $this->sendResponse('InUseTackle fetched successfully', $tackle, 200);
            }else{
                return $this->sendError('InUseTackle not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch tackle', $e->getMessage(), 500);
        }
    }
}
