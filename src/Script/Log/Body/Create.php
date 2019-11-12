<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

$req = Core::request();
Core::class("Log/Body/Body_Create");
$object = new Body_Create(Auth::$session->account->id);

Core::plugin('Vali/Vali_Number');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Time');

$object->weight = Vali_Number::val("weight", $req->weight, true, 1, false);
$object->fat = Vali_Number::val("fat", $req->fat, false, 1, false);
$object->date = Vali_Date::val("date", $req->date, false, "1900-01-01", date('Y-m-d'));
$object->time = Vali_Time::val("time", $req->time, false);

$object->create();

$tmpVal = Body::formResponse((array) $object);
Response::data([$tmpVal->id => $tmpVal]);
Response::success(200, "Request successful handled");