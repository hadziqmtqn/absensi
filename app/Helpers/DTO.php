<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class DTO
{
    protected static $responseData = [
        'message' => null,
        'token' => null,
        'error' => null,
        'data' => null,
    ];
    protected static $code = Response::HTTP_NOT_FOUND;

    public static function ResponseDTO($message = null, $token = null, $error = null, $data = null, $code = Response::HTTP_NOT_FOUND)
    {
        $responseData['message'] = $message;
        $responseData['token'] = $token;
        $responseData['error'] = $error;
        $responseData['data'] = $data;
        $code = $code;

        return response()->json($responseData, $code);
    }
}
