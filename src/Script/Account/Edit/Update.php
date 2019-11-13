<?php

Core::plugin('Vali/Vali_Mail');
Core::plugin('Vali/Vali_Username');
Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Date');

Core::class("Auth/Auth");
Core::class('Account/Account_Update');

$req = Core::request();
$account_id = Vali_String::val("account", Router::$params[0], true);

Auth::getSession(true, true);
Auth::permit($account_id, "owner", "admin");

$obj = new Account_Update($account_id);

$obj->mail = Vali_Mail::val("mail", $req->mail, true, 5, 90);

$existing = Account::getIdByMail($obj->mail);
if (!in_array($existing, [null, $account_id]))
    throw new ApiException(400, "A1005", "Mail already in use");
else if (!$existing) $obj->setStatus('unverified');

$obj->username = Vali_Username::val("username", $req->username, true, 6, 90);
if (!in_array(Account::getIdByUsername($obj->username), [null, $account_id]))
    throw new ApiException(400, "A1006", "Username already in use");

$obj->firstname = Vali_String::val("firstname", $req->firstname, true, 1, 150, true);
$obj->lastname = Vali_String::val("lastname", $req->lastname, true, 1, 150, true);
$obj->birthdate = Vali_Date::val("birthdate", $req->birthdate, false, "1900-01-01", date('Y-m-d'));

if (!isset($req->avatar)) throw new ApiException(422, "X0001", "Avatar (ID) required");
if (gettype($req->avatar) === "object") $obj->avatar = Vali_String::val("avatar->id", $req->avatar->id, true, 30, 50, true);
else $obj->avatar = Vali_String::val("avatar", $req->avatar, true, 30, 50, true);

$obj->update();

Response::success(200, "Request successful handled");