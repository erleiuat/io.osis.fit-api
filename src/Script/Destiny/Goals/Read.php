<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);

Core::plugin("Vali/Vali_String");
$id = Vali_String::val("accountID", Router::$params[0], true, 40, 80, true);
Auth::permit($id, "owner", "admin");

Core::class("Destiny/Goals/Goals_Read");
$obj = new Goals_Read($id);
$obj->read();

Response::data([
    "weight" => $obj->weight ? (double) $obj->weight : null,
    "fat" => $obj->fat ? (double) $obj->fat : null,
    "date" => $obj->date
]);

Response::success(200, "Request successful handled");