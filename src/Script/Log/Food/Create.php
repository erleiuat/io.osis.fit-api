<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

$req = Core::request();
Core::class("Log/Food/Food_Create");
$food = new Food_Create(Auth::$session->account->id);

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Number');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Time');

$food->title = Vali_String::val("title", $req->title, false, 1, 120, true);
$food->total_calories = Vali_Number::val("totalCalories", $req->totalCalories, false, 1, false);
$food->total_fat = Vali_Number::val("totalFat", $req->totalFat, false, 1, false);
$food->total_protein = Vali_Number::val("totalProtein", $req->totalProtein, false, 1, false);
$food->portion_size = Vali_Number::val("portionSize", $req->portionSize, false, 1, false);
$food->date = Vali_Date::val("date", $req->date, false, "1900-01-01", date('Y-m-d'));
$food->time = Vali_Time::val("time", $req->time, false);

$food->create();

$tmpVal = Food::formResponse((array) $food);
Response::data([$tmpVal->id => $tmpVal]);
Response::success(200, "Request successful handled");