<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait {

    /**
     * Create a new controller instance.
     *
     * @param $msg
     * @param $statusCode
     * @return JsonResponse
     */
    public function returnError($msg, $statusCode): JsonResponse
    {
        return response()->json([
            'status'    => false,
            'msg'       => $msg,
        ], $statusCode, ['Content-Type' => 'application/json;charset=UTF-8'],
            JSON_UNESCAPED_UNICODE);
    }

    public function returnErrorData($status,$value,$statusCode): JsonResponse
    {
        return response()->json([
            'status'    => false,
            "data"      => $value,
            
        ], $statusCode, ['Content-Type' => 'application/json;charset=UTF-8'],
            JSON_UNESCAPED_UNICODE);
    }


    /**
     * Create a new controller instance.
     *
     * @param $msg
     * @param $statusCode
     * @return JsonResponse
     */
    public function returnSuccess($msg, $statusCode): JsonResponse
    {
        return response()->json([
            'status'    => true,
            'msg'       => $msg
        ], $statusCode, ['Content-Type' => 'application/json;charset=UTF-8'],
            JSON_UNESCAPED_UNICODE);
    }

    /**
     * Create a new controller instance.
     *
     * @param $msg
     * @param $value
     * @param $statusCode
     * @return JsonResponse
     */
    public function returnData($value, $status,$statusCode): JsonResponse
    {
        return response()->json([
            'status'    => true,
            "data"      => $value
        ], $statusCode, ['Content-Type' => 'application/json;charset=UTF-8'],
            JSON_UNESCAPED_UNICODE);
    }
}