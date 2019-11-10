<?php

Core::plugin('Vali/Vali_Mail');
Core::plugin('Vali/Vali_Username');
Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Password');

Core::class("Auth/Auth");
Core::class('Account/Account_Update');

$req = Core::request();
$patchable = ["mail", "username", "firstname", "lastname", "birthdate", "locale", "avatar"];
$account_id = Vali_String::val("account", Router::$params[0], true);

Auth::getSession(true, true);
Auth::permit($account_id, "owner", "admin");

$Account = new Account_Update($account_id, true);

foreach ($req as $key => $value) {
    if (in_array($key, $patchable)) {
        if ($key === "mail") {
            $Account->mail = Vali_Mail::val("mail", $value, true, 5, 90);
            $existing = Account::getIdByMail($Account->mail);
            if (!in_array($existing, [null, $account_id]))
                throw new ApiException(400, "A1005", "Mail already in use");
            else if (!$existing) $Account->setStatus('unverified');

        } else if ($key === "username") {
            $Account->username = Vali_Username::val("username", $value, true, 6, 90);
            if (!in_array(Account::getIdByUsername($Account->username), [null, $account_id]))
                throw new ApiException(400, "A1006", "Username already in use");

        } else if ($key === "avatar") {
            if (gettype($value) === "object") $Account->avatar = Vali_String::val("avatar->id", $value->id, true, 30, 50, true);
            else $Account->avatar = Vali_String::val("avatar", $value, false, 30, 50, true);

        } else {
            $Account->$key = $value;
        }
    }
}

$Account->firstname = Vali_String::val("firstname", $Account->firstname, true, 1, 150, true);
$Account->lastname = Vali_String::val("lastname", $Account->lastname, true, 1, 150, true);
$Account->birthdate = Vali_Date::val("birthdate", $Account->birthdate, false, "1900-01-01", date('Y-m-d'));
$Account->locale = Vali_String::val("locale", $Account->locale, false, 2, 5, true);

$Account->update();

Response::success(200, "Request successful handled");