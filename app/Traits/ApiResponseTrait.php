<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{
    /**
     * Send a simple response with a message.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function sendResponse(string $message,$data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Send a response with a paginated list of data.
     *
     * @param LengthAwarePaginator $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function sendPaginatedResponse($data, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $data->count().' items retrieved successfully',
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ],
        ], $statusCode);
    }
    public function sendError(string $message, $error, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'error' => $error
        ], $statusCode);
    }
}
