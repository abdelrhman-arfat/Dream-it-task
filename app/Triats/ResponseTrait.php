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
        $paginationData=null,
        $message = "success",
        $code = 200
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'success' => true,
            'meta_data' => [
                'current_page' => $paginationData->currentPage(),
                'last_page' => $paginationData->lastPage(),
                'per_page' => $paginationData->perPage(),
                'total' => $paginationData->total(),
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
