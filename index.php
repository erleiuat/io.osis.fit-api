<?php

require "env.php";
require "src/Core/autoload.php";

error_reporting(ENV_Main::error_reports);
date_default_timezone_set(ENV_Main::timezone);

header("Access-Control-Allow-Origin: " . ENV_Main::cors);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400");

try {

    Log::init();
    Database::connect();
    Router::getRoute();

    header("Access-Control-Allow-Methods: " . Router::$route->method);

    if (!Router::$route->script) Response::success(200, "Route has no script");
    else {
        Log::setProcess(Router::$route->process);
        require Router::$route->script;
    }

} catch (\Exception $e) {

    Log::addInformation("ERR_MSG=" . $e->getMessage());
    
    if(get_class($e) === "ApiException") {
        Log::addInformation("ERR_CODE=" . $e->getErrorCode());
        Log::addInformation("ERR_RESP=" . $e->getResponseCode());
        if (Log::$level === "trace") {
            if ($e->getCode() === 500) Log::setLevel('error');
            else Log::setLevel('debug');
        }
        Response::error($e->getResponseCode(), $e->getErrorCode(), $e->getMessage());
    } else {
        Log::addInformation("ERR_CODE=G0001(" . $e->getCode() . ")");
        if (Log::$level === "trace") Log::setLevel('fatal');
        Response::error(500, 'G0001', $e->getMessage() . ' (' . $e->getCode() . ')');
    }

}

Response::send();

Log::write(ENV_Main::log_levels);