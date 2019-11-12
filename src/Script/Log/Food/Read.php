<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class("Log/Food/Food_Read");
$food = new Food_Read(Auth::$session->account->id);

Core::plugin('Vali/Vali_Date');
$from = Vali_Date::val("from", $_GET['from'], false, "1900-01-01", date('Y-m-d'));
$to = Vali_Date::val("to", $_GET['to'], false, "1900-01-01", date('Y-m-d'));

$response = [];
$entries = $food->read($from, $to);
foreach ($entries as $key => $value) array_push($response, Food::formResponse($value));

Response::data($response);
Response::success(200, "Request successful handled");