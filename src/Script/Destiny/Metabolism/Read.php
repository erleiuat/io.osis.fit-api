<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);

Core::plugin("Vali/Vali_String");
$id = Vali_String::val("accountID", Router::$params[0], true, 40, 80, true);
Auth::permit($id, "owner", "admin");

Core::class("Destiny/Metabolism/Metabolism_Read");
$obj = new Metabolism_Read($id);
$obj->read();

Response::data([
    "gender" => $obj->gender,
    "height" => $obj->height ? (double) $obj->height : null,
    "birthdate" => $obj->birthdate,
    "pal" => $obj->pal ? (double) $obj->pal : null
]);

Response::success(200, "Request successful handled");