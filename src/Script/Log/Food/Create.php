<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

$req = Core::request();
Core::class("Log/Food/Food_Create");
$object = new Food_Create(Auth::$session->account->id);

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Number');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Time');

$object->title = Vali_String::val("title", $req->title, false, 1, 120, true);
$object->total_calories = Vali_Number::val("totalCalories", $req->totalCalories, false, 1, false);
$object->total_fat = Vali_Number::val("totalFat", $req->totalFat, false, 1, false);
$object->total_protein = Vali_Number::val("totalProtein", $req->totalProtein, false, 1, false);
$object->portion_size = Vali_Number::val("portionSize", $req->portionSize, false, 1, false);
$object->date = Vali_Date::val("date", $req->date, false, "1900-01-01", false);
$object->time = Vali_Time::val("time", $req->time, false);

$object->create();

$tmpVal = Food::formResponse((array) $object);
Response::data([$tmpVal->id => $tmpVal]);
Response::success(200, "Request successful handled");