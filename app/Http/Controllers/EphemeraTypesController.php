<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EphemeraType;

class EphemeraTypesController extends Controller
{
    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'type' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
            $type = EphemeraType::create($validatedData);
            return $this->sendResponse('Type Created Successfully', $type, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create type',$e->getMessage(), 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15); # Default to 15 items per page
            $types = EphemeraType::paginate($perPage);
            return $this->sendPaginatedResponse($types, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch types', $e->getMessage(), 500);
        }
    }


    public function deleteType(Request $request)
    {
        try {
            $type = EphemeraType::find($request->id);
            $type->delete();
            return $this->sendResponse('Type deleted successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete type', $e->getMessage(), 500);
        }
    }

    public function editType(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'type' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
            $type = EphemeraType::find($request->id);
            $type->update($validatedData);
            return $this->sendResponse('Type updated successfully', $type, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to update type', $e->getMessage(), 500);
        }
    }
    public function show($id)
    {
        try {
            $type = EphemeraType::find($id);
            return $this->sendResponse('Type fetched successfully', $type, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch type', $e->getMessage(), 500);
        }
    }
}
