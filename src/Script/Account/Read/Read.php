<?php

Core::plugin('Vali/Vali_String');
Core::class("Auth/Auth");
Core::class('Account/Account_Read');

$account_id = Vali_String::val("account", Router::$params[0], true);
Auth::getSession(true, true);
Auth::permit($account_id, "owner", "admin");

$Account = new Account_Read($account_id, true);

Response::data([
    "mail" => $Account->mail,
    "username" => $Account->username,
    "level" => $Account->level,
    "status" => $Account->status,
    "firstname" => $Account->firstname,
    "lastname" => $Account->lastname,
    "birthdate" => $Account->birthdate,
    "locale" => $Account->locale
]);

// TODO: Add Avatar

Response::success(200, "Request successful handled");