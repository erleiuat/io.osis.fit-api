<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);

Core::plugin("Vali/Vali_String");
$id = Vali_String::val("accountID", Router::$params[0], true, 40, 80, true);
Auth::permit($id, "owner", "admin");

Core::class("Destiny/Metabolism/Metabolism_Patch");
$obj = new Metabolism_Patch($id);
$obj->read();

Core::plugin("Vali/Vali_Number");
Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Date");

$req = Core::request();
if (isset($req->gender)) $obj->gender = Vali_String::val("gender", $req->gender, false, 3, 7, true);
if (isset($req->height)) $obj->height = Vali_Number::val("height", $req->height, false, 10, 500);
if (isset($req->birthdate)) $obj->birthdate = Vali_Date::val("birthdate", $req->birthdate, false, "1900-01-01", date('Y-m-d'));
if (isset($req->pal)) $obj->pal = Vali_Number::val("pal", $req->pal, false, 0.1, 10);

$obj->patch();

Response::success(200, "Request successful handled");