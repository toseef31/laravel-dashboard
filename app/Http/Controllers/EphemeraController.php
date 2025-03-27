<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ephemera;
use DB;

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

            $query->with('ephemeraMedia', 'ephemeraType');
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
            if ($ephemera) {
                $ephemera->delete();
                return $this->sendResponse('Ephemera deleted successfully', null, 200);
            } else {
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
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'ephemeras'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum value of the ephemera_id column (ignoring the 'E' prefix)
            $maxEphemeraId = Ephemera::max(DB::raw("CAST(SUBSTRING(ephemera_id, 2) AS UNSIGNED)"));

            // Determine the next ID to use, ensuring it's greater than both auto-increment and the highest ephemera_id
            $nextIdToUse = max($nextAutoIncrementId, $maxEphemeraId + 1);

            // Generate the next ephemera_id with 'E' prefix
            $nextEphemeraId = 'E' . $nextIdToUse;

            return $this->sendResponse('Next ephemera ID fetched successfully', ['ephemera_id' => $nextEphemeraId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next ephemera ID', $e->getMessage(), 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $ephemera = Ephemera::create($request->all());
            return $this->sendResponse('Ephemera created successfully', $ephemera, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create ephemera', $e->getMessage(), 500);
        }
    }
    public function editEphemera(Request $request)
    {
        try {
            $ephemera = Ephemera::find($request->id);
            if ($ephemera) {
                $ephemera->update($request->all());
                return $this->sendResponse('Ephemera updated successfully', $ephemera, 200);
            } else {
                return $this->sendError('Ephemera not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update ephemera', $e->getMessage(), 500);
        }
    }
    public function show($id)
    {
        try {
            $ephemera = Ephemera::with('ephemeraMedia')->find($id);
            if ($ephemera) {
                return $this->sendResponse('Ephemera fetched successfully', $ephemera, 200);
            } else {
                return $this->sendError('Ephemera not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch ephemera', $e->getMessage(), 500);
        }
    }
}
