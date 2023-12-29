<?php

namespace App\Traits;

trait ResponseTrait
{
    public function success($message = "Request successful", $data = [], $status = 200, $verified = true)
    {
        return response([
            'success' => true,
            'message' => $message,
            'verified' => $verified,
            'data' => $data,
        ], $status);
    }

    protected function fail($message = "Something went wrong", $status = 422)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
