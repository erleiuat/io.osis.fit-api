<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class("Template/Template_Create");
$obj = new Template_Create(Auth::$session->account->id);

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Number');

$req = Core::request();
$obj->title = Vali_String::val("title", $req->title, false, 1, 120, true);
$obj->calories_per_100 = Vali_Number::val("caloriesPer100", $req->caloriesPer100, false, 0.01, false);
$obj->fat_per_100 = Vali_Number::val("fatPer100", $req->fatPer100, false, 0.01, false);
$obj->protein_per_100 = Vali_Number::val("proteinPer100", $req->proteinPer100, false, 0.01, false);
$obj->carbs_per_100 = Vali_Number::val("carbsPer100", $req->carbsPer100, false, 0.01, false);
$obj->portion_size = Vali_Number::val("portionSize", $req->portionSize, false, 0.01, false);

if (gettype($req->image) === "object") $obj->image = Vali_String::val("image->id", $req->image->id, false, 30, 50, true);
else $obj->image = Vali_String::val("image", $req->image, false, 30, 50, true);

$obj->create();

$resp = Template::formResponse((array) $obj);
if ($resp->image) {
  Core::class('Image/Image_Read');
  $image = new Image_Read(Auth::$session->account->id, $resp->image);
  $resp->image = Image::getObject($image);
}

Response::data($resp);

Response::success(200, "Request successful handled");
