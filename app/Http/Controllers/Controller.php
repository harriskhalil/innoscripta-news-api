<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function response($message="", $data=[], $code=200, $status="SUCCESS") {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ], $code);
    }

    protected function error($message, $code=500, $data=[]) {
        return $this->response($message, $data, $code, "ERROR");
    }
}
