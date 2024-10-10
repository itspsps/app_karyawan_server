<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class WebJsonResponse
{
    protected static $response = [
        'status' => true,
        'message' => null,
        'table' => null
    ];

    public static function SuccessResponse(string $message, string $table): JsonResponse
    {
        self::$response['message'] = $message;
        self::$response['table'] = $table;
        return response()->json(self::$response, 200);
    }
    public static function ErrorResponse(mixed $error, int $code): JsonResponse
    {

        unset(self::$response['message']);
        unset(self::$response['table']);

        self::$response['error'] = $error;
        self::$response['status'] = false;
        return response()->json(self::$response, $code);
    }
}
