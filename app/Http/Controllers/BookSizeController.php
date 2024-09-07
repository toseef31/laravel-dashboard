<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookSize;

class BookSizeController extends Controller
{
    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'size' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
            $size = BookSize::create($validatedData);
            return $this->sendResponse('Size Created Successfully', $size, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create size',$e->getMessage(), 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15); # Default to 15 items per page
            $sizes = BookSize::paginate($perPage);
            return $this->sendPaginatedResponse($sizes, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch sizes', $e->getMessage(), 500);
        }
    }


    public function deleteSize(Request $request)
    {
        try {
            $size = BookSize::find($request->id);
            $size->delete();
            return $this->sendResponse('Size deleted successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete size', $e->getMessage(), 500);
        }
    }

    public function editSize(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'size' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
            $size = BookSize::find($request->id);
            $size->update($validatedData);
            return $this->sendResponse('Size updated successfully', $size, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to update size', $e->getMessage(), 500);
        }
    }
    public function show($id)
    {
        try {
            $size = BookSize::find($id);
            return $this->sendResponse('Size fetched successfully', $size, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch size', $e->getMessage(), 500);
        }
    }
}
