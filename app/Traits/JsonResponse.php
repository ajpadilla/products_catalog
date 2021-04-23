<?php


namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

trait JsonResponse
{
    /**
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorResponse($message, $code)
    {
        return Response::json([
            'error'     => true,
            'message'   => $message
        ], $code);
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = [])
    {
        return Response::json([
            'error'     => false,
            'data'   => $data
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @param string $error
     * @return \Illuminate\Http\JsonResponse
     */
    public static function badRequest($error = '')
    {
        return self::errorResponse($error ? $error : 'Bad Request', 400);
    }

    /**
     * @param string $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function internalServerError($errors = ''): \Illuminate\Http\JsonResponse
    {
        Log::error($errors, ['API Internal server error']);
        return self::errorResponse($errors ? $errors : 'Internal server error', 500);
    }
}
