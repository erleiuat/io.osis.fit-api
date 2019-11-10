<?php

class Response {

    private static $data = null;
    private static $message = null;
    private static $success = false;
    private static $error_code = null;
    private static $response_code = null;

    public static function data($data = []) {
        self::$data = $data;
    }

    public static function success($response_code = 201, $message) {
        self::$success = true;
        self::$response_code = $response_code;
        self::$message = $message;
    }

    public static function error($response_code = 500, $error_code = 'G0001', $message) {
        self::$success = false;
        self::$response_code = $response_code;
        self::$error_code = $error_code;
        self::$message = $message;
    }

    public static function send() {

        if (self::$success === true) $reply = [
            "success" => true,
            "message" => self::$message,
            "data" => self::$data
        ];
        else $reply = [
            "success" => false,
            "message" => self::$message,
            "error_code" => self::$error_code
        ];

        http_response_code(self::$response_code);
        echo json_encode($reply);

    }

}