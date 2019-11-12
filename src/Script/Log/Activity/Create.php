<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

$req = Core::request();
Core::class("Log/Activity/Activity_Create");
$object = new Activity_Create(Auth::$session->account->id);

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Number');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Time');

$object->title = Vali_String::val("title", $req->title, false, 1, 120, true);
$object->burned_calories = Vali_Number::val("burned_calories", $req->burnedCalories, false, 1, false);
$object->type = Vali_String::val("type", $req->type, false, 1, 20, true);
$object->date = Vali_Date::val("date", $req->date, false, "1900-01-01", date('Y-m-d'));
$object->time = Vali_Time::val("time", $req->time, false);

$object->create();

$tmpVal = Activity::formResponse((array) $object);
Response::data([$tmpVal->id => $tmpVal]);
Response::success(200, "Request successful handled");