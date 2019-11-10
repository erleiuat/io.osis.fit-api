<?php

Core::class("Auth/Auth");
Core::class('Account/Account_Read');

Auth::getSession(true, true);
Auth::permit(false, "admin");

$Account = new Account_Read();
$values = $Account->all(true);

Response::data($values);

// TODO: Add Avatar

Response::success(200, "Request successful handled");