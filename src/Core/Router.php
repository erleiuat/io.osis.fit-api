<?php

Core::environment("ENV_Routes");

class Router {

    public static $params = null;
    public static $route = null;

    public static function getRoute($base = ENV_Main::base, $scripts = ENV_Main::path_script) {

        $path = false;
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $url = trim(str_replace($base, '', strtolower(parse_url($_SERVER['REQUEST_URI'])['path'])), '/');
        $props = explode("/", $url);
        $version = $props[0];

        Log::addInformation("VERSION=" . $version);
        Log::addInformation("PATH=" . $url);
        Log::addInformation("METHOD=" . $method);

        if ($version !== "v1") throw new ApiException(404, "R0101", "Version mismatch");
        if ($method === "options") {
            self::$route = (object) [
                "version" => "v1",
                "method" => "PUT, PATCH, DELETE, POST, GET, OPTIONS",
                "script" =>  false
            ];
            return;
        }

        $tmp = '';
        $len = count($props) - 1;
        for ($i = 1; $i <= $len; $i++) {
            unset($props[$i - 1]);
            if ($i > 1) $tmp .= '.';
            $tmp .= $props[$i];
            if (array_key_exists($tmp . '.' . $method, ENV_Routes::v1)) {
                unset($props[$i]);
                if (count($props) < 1) $path = $tmp . '.' . $method;
            } else if (array_key_exists($tmp . '.*.' . $method, ENV_Routes::v1)) {
                unset($props[$i]);
                $path = $tmp . '.*.' . $method;
            }
        }

        if (!$path) throw new ApiException(404, "R0102", "Route not found");
        $route = ENV_Routes::v1[$path];
        self::$params = array_values($props);

        self::$route = (object) [
            "version" => "v1",
            "process" => $route["process"],
            "name" => $route["name"],
            "method" => $route["method"],
            "script" =>  str_replace("@scripts@", $scripts, $route["script"]) . '.php'
        ];
        
    }

}