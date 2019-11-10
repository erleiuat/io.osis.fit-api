<?php

Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Number");

Core::class("Auth/Auth");
Core::class("Auth/Session/Session_Delete");

Auth::getSession(true, true);
Auth::permit(null, "admin");

if (isset(Router::$params[0])) {
    $acc_id = Vali_String::val("account_id", Router::$params[0], true, 5, 260, true);
    Session_Delete::byAccountId($acc_id);
} else {
    Session_Delete::all();
}

Response::success(200, "Request successful handled");