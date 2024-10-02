<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rods;
use DB;

class RodsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = Rods::query();
            $query->orderBy('id', 'desc');
    
            if ($request->has('rod_id')) {
                $query->where('rod_id', $request->input('rod_id'));
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
    
            $query->with('rodsMedia');
            $rodss = $query->paginate($perPage);
            return $this->sendPaginatedResponse($rodss, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch rods(s)', $e->getMessage(), 500);
        }
    }

    public function deleteRods(Request $request)
    {
        try {
            $rods = Rods::find($request->id);
            if($rods){
                $rods->delete();
                return $this->sendResponse('Rods deleted successfully', null, 200);
            }else{
                return $this->sendError('Rods not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete rods', $e->getMessage(), 500);
        }
    }

    public function getNextRodsId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
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
            
            return $this->sendResponse('Next rods ID fetched successfully', ['rods_id' => $nextRodsId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next rods ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request){
        try {
            $rods = Rods::create($request->all());
            return $this->sendResponse('Rods created successfully', $rods, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create rods', $e->getMessage(), 500);
        }
    }
    public function editRods(Request $request){
        try {
            $rods = Rods::find($request->id);
            if($rods){
                $rods->update($request->all());
                return $this->sendResponse('Rods updated successfully', $rods, 200);
            }else{
                return $this->sendError('Rods not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update rods', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $rods = Rods::find($id);
            if($rods){
                return $this->sendResponse('Rods fetched successfully', $rods, 200);
            }else{
                return $this->sendError('Rods not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch rods', $e->getMessage(), 500);
        }
    }
}
