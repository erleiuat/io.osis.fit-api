<?php

Core::plugin('Vali/Vali_Number');
Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Mail');
Core::plugin('Vali/Vali_Password');
Core::plugin('Vali/Vali_Boolean');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Time');

Core::class("Auth/Auth");

Auth::getSession(true, true);
$req = Core::request();

$body = [
    "number" => Vali_Number::val("number", $req->number, false, -20, 10),
    "string" => Vali_String::val("string", $req->string, false, 1, 5, false),
    "mail" => Vali_Mail::val("mail", $req->mail, true, 1),
    "password" => Vali_Password::val("password", $req->password, true, 8, false, ['uppercase', 'lowercase', 'number', 'special']),
    "boolean" =>  Vali_Boolean::val("boolean", $req->boolean, true),
    "date" => Vali_Date::val("date", $req->date, true, "1900-02-03", date('Y-m-d')),
    "time" => Vali_Time::val("time", $req->time, true, "12:05", "14:12")
];

Response::data([
    "body" => $body,
    "session" => Auth::$session
]);

Response::success(200, "Request successful handled");