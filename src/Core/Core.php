<?php

class Core {

    public static function request() {
        $data = json_decode(file_get_contents("php://input"));
        if (json_last_error() !== 0) throw new ApiException(400, "G0002", "Invalid Json");
        return $data;
    }

    public static function plugin($name, $path = ENV_Main::path_plugin) {
        require_once $path . "/" . $name . ".php";
    }

    public static function class($name, $path = ENV_Main::path_class) {
        require_once $path . "/" . $name . ".php";
    }

    public static function library($name, $path = ENV_Main::path_library) {
        require_once $path . "/" . $name . ".php";
    }

    public static function environment($name, $path = ENVPATH) {
        require_once $path . "/" . $name . ".php";
    }

    public static function randomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}