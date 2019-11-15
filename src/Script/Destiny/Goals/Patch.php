<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);

Core::plugin("Vali/Vali_String");
$id = Vali_String::val("accountID", Router::$params[0], true, 40, 80, true);
Auth::permit($id, "owner", "admin");

Core::class("Destiny/Goals/Goals_Patch");
$obj = new Goals_Patch($id);
$obj->read();

Core::plugin("Vali/Vali_Number");
Core::plugin("Vali/Vali_Date");

$req = Core::request();
if (isset($req->weight)) $obj->weight = Vali_Number::val("weight", $req->weight, false, 1, 500);
if (isset($req->fat)) $obj->fat = Vali_Number::val("fat", $req->fat, false, 1, 500);
if (isset($req->date)) $obj->date = Vali_Date::val("date", $req->date, false, date('Y-m-d'), false);

$obj->patch();

Response::success(200, "Request successful handled");