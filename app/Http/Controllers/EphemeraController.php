<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ephemera;

class EphemeraController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = Ephemera::query();
            $query->orderBy('id', 'desc');
    
            if ($request->has('ephemera_id')) {
                $query->where('ephemera_id', $request->input('ephemera_id'));
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
    
            $query->with('ephemeraMedia');
            $ephemeras = $query->paginate($perPage);
            return $this->sendPaginatedResponse($ephemeras, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch ephemera(s)', $e->getMessage(), 500);
        }
    }

    public function deleteEphemera(Request $request)
    {
        try {
            $ephemera = Ephemera::find($request->id);
            if($ephemera){
                $ephemera->delete();
                return $this->sendResponse('Ephemera deleted successfully', null, 200);
            }else{
                return $this->sendError('Ephemera not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete ephemera', $e->getMessage(), 500);
        }
    }

    public function getNextEphemeraId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
            $lastId = Ephemera::max('id');
            
            // Calculate the next ID
            $nextId = $lastId ? $lastId + 1 : 1;
            
            // Format the next book ID with the 'B' prefix and leading zeros
            $nexEphemeraId = 'E' . $nextId;
            
            return $this->sendResponse('Next ephemera ID fetched successfully', ['ephemera_id' => $nexEphemeraId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next ephemera ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request){
        try {
            $ephemera = Ephemera::create($request->all());
            return $this->sendResponse('Ephemera created successfully', $ephemera, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create ephemera', $e->getMessage(), 500);
        }
    }
    public function editEphemera(Request $request){
        try {
            $ephemera = Ephemera::find($request->id);
            if($ephemera){
                $ephemera->update($request->all());
                return $this->sendResponse('Ephemera updated successfully', $ephemera, 200);
            }else{
                return $this->sendError('Ephemera not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update ephemera', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $ephemera = Ephemera::find($id);
            if($ephemera){
                return $this->sendResponse('Ephemera fetched successfully', $ephemera, 200);
            }else{
                return $this->sendError('Ephemera not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch ephemera', $e->getMessage(), 500);
        }
    }
}
