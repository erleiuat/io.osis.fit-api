<?php

Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Number");

Core::class("Account/Account_Read");
Core::class("Image/Image_Admin");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");


if (isset($_GET['identity'])) $identity = Vali_String::val("identity", $_GET['identity'], false, 1, 100);
else $identity = false;

if (isset($_GET['status'])) $status = Vali_String::val("status", $_GET['status'], false, 1, 20);
else $status = false;

if (isset($_GET['from'])) $from = Vali_Number::val("from", $_GET['from'], false, 0);
else $from = 0;

if (isset($_GET['to'])) $to = Vali_Number::val("to", $_GET['to'], false, 1);
else $to = $from + 100;


$entries = Account_Read::all($from, $to, $identity, $status);

foreach ($entries as $key => $value) {
    if($value["avatar"]) {
        $entries[$key]["avatar"] = Image_Admin::readObject($value["avatar"]);
    }
}

Response::data($entries);

Response::success(200, "Request successful handled");