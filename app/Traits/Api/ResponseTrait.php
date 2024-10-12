<?php

namespace App\Traits\Api;

trait ResponseTrait
{
    public function successResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public function errorResponse($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function returnValidationError($code = 422, $validator)
    {
        $response = [
            'success' => false,
            'message' => 'Validation Error',
            'data' => $validator->errors()
        ];

        return response()->json($response, $code);
    }
}
