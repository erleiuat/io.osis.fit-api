<?php

Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Number");

Core::class("Account/Account_Update");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");

$req = Core::request();
$acc_id = Vali_String::val("account_id", Router::$params[0], true, 5, 260, true);

$Account = new Account_Update($acc_id);

if (isset($req->level)) {
    $level = Vali_String::val("level", $req->level, true, 1, 20);
    $Account->setLevel($level);
}

if (isset($req->status)) {
    $status = Vali_String::val("status", $req->status, true, 1, 20);
    $Account->setStatus($status);
}


Response::success(200, "Request successful handled");