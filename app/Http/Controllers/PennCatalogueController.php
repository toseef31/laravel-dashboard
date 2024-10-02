<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PennCatalogue;
use DB;

class PennCatalogueController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = PennCatalogue::query();
            $query->orderBy('id', 'desc');

    
            if ($request->has('name')) {
                $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
            }

            if ($request->has('year')) {
                $query->where('year', 'LIKE', '%' . $request->input('year') . '%');
            }
    
            if ($request->has('catalogue_no')) {
                $query->where('catalogue_no', 'LIKE', '%' . $request->input('catalogue_no') . '%');
            }
    
            if ($request->has('condition')) {
                $query->where('condition', 'LIKE', '%' . $request->input('condition') . '%');
            }
            if ($request->has('cost_price')) {
                $query->where('cost_price', 'LIKE', '%' . $request->input('cost_price') . '%');
            }
    
            $query->with('pennCatalogueMedia');
            $pennCatalogues = $query->paginate($perPage);
            return $this->sendPaginatedResponse($pennCatalogues, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch pennCatalogue(s)', $e->getMessage(), 500);
        }
    }

    public function deletePennCatalogue(Request $request)
    {
        try {
            $pennCatalogue = PennCatalogue::find($request->id);
            if($pennCatalogue){
                $pennCatalogue->delete();
                return $this->sendResponse('PennCatalogue deleted successfully', null, 200);
            }else{
                return $this->sendError('PennCatalogue not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete pennCatalogue', $e->getMessage(), 500);
        }
    }

    public function store(Request $request){
        try {
            $pennCatalogue = PennCatalogue::create($request->all());
            return $this->sendResponse('PennCatalogue created successfully', $pennCatalogue, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create pennCatalogue', $e->getMessage(), 500);
        }
    }
    public function editPennCatalogue(Request $request){
        try {
            $pennCatalogue = PennCatalogue::find($request->id);
            if($pennCatalogue){
                $pennCatalogue->update($request->all());
                return $this->sendResponse('PennCatalogue updated successfully', $pennCatalogue, 200);
            }else{
                return $this->sendError('PennCatalogue not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update pennCatalogue', $e->getMessage(), 500);
        }
    }
    public function show($id){
        try {
            $pennCatalogue = PennCatalogue::find($id);
            if($pennCatalogue){
                return $this->sendResponse('PennCatalogue fetched successfully', $pennCatalogue, 200);
            }else{
                return $this->sendError('PennCatalogue not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch pennCatalogue', $e->getMessage(), 500);
        }
    }
}
