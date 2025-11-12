<?php

namespace App\Triats;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function successResponse(
        $data = null,
        $message = "success",
        $code = 200
    ):JsonResponse{
        return response()->json([
            'message' => $message,
            'data' => $data,
            'success' => true,
        ] , $code);
    }

    public function paginationResponse(
        $data = null,
        $message = "success",
        $code = 200
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data->items(),
            'success' => true,
            'meta_data' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]
        ], $code);
    }

    public function errorResponse(
        $message = "error",
        $code = 400
    ):JsonResponse{
        return response()->json([
            'message' => $message,
            'data' => null,
            'success' => false,
        ] , $code);
    }
}
