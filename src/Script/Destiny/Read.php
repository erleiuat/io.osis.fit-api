<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);

Core::plugin("Vali/Vali_String");
$id = Vali_String::val("accountID", Router::$params[0], true, 40, 80, true);
Auth::permit($id, "owner", "admin");

Core::class("Destiny/Goals/Goals_Read");
$obj1 = new Goals_Read($id);
$obj1->read();

Core::class("Destiny/Metabolism/Metabolism_Read");
$obj2 = new Metabolism_Read($id);
$obj2->read();

Response::data([
    "goals" => [
        "weight" => $obj1->weight ? (double) $obj1->weight : null,
        "fat" => $obj1->fat ? (double) $obj1->fat : null,
        "date" => $obj1->date
    ],
    "metabolism" => [
        "gender" => $obj2->gender,
        "height" => $obj2->height ? (double) $obj2->height : null,
        "birthdate" => $obj2->birthdate,
        "pal" => $obj2->pal ? (double) $obj2->pal : null
    ]
]);

Response::success(200, "Request successful handled");