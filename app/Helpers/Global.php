<?php

function success($data, $message = "") {
    return response()->json([
        "status" => true,
        "message" => $message,
        "data" => $data,
    ], 200);
}

function fails($message = "", $errorCode, $httpCode = 400) {
    return response()->json([
        "status" => false,
        "message" => $message,
        "data" => null,
        "errorCode" => $errorCode,
    ], $httpCode);
}
