<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::plugin("Vali/Vali_Number");
Core::class("Template/Template_Edit");
$id = Vali_Number::val("id", Router::$params[0], true, 1, false);
$obj = new Template_Edit(Auth::$session->account->id, $id);

Core::plugin("Vali/Vali_String");
$req = Core::request();
$obj->title = Vali_String::val("title", $req->title, false, 1, 120, true);
$obj->calories_per_100 = Vali_Number::val("caloriesPer100", $req->caloriesPer100, false, 1, false);
$obj->fat_per_100 = Vali_Number::val("fatPer100", $req->fatPer100, false, 1, false);
$obj->protein_per_100 = Vali_Number::val("proteinPer100", $req->proteinPer100, false, 1, false);
$obj->portion_size = Vali_Number::val("portionSize", $req->portionSize, false, 1, false);

if (gettype($req->image) === "object") $obj->image = Vali_String::val("image->id", $req->image->id, false, 30, 50, true);
else $obj->image = Vali_String::val("image", $req->image, false, 30, 50, true);

$obj->edit();

Response::success(200, "Request successful handled");