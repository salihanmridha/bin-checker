<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseJson
{
    /**
     * @param int $status
     * @param bool $success
     * @param string $message
     * @param Object|null $data
     * @return JsonResponse
     */
    public function data(int $status, bool $success, bool $isValid, string $message, ?Object $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "status" => $status,
            "success" => $success,
            "isValid" => $isValid,
            "message" => $message,
            "data" => $data ? $data : [],
        ]);
    }
}
