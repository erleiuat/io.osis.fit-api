<?php

Core::plugin('Vali/Vali_Mail');
Core::plugin('Vali/Vali_Username');
Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Date');
Core::plugin('Vali/Vali_Password');

Core::class('Auth/Auth_Create');
Core::class('Account/Account_Create');

$req = Core::request();

$Account = new Account_Create();
$Account->mail = Vali_Mail::val("mail", $req->mail, true, 5, 90);
$Account->username = Vali_Username::val("username", $req->username, true, 6, 90);

if (Account::getIdByMail($Account->mail))
    throw new ApiException(400, "A1005", "Mail already in use");

if (Account::getIdByUsername($Account->username))
    throw new ApiException(400, "A1006", "Username already in use");

$Account->firstname = Vali_String::val("firstname", $req->firstname, true, 1, 150, true);
$Account->lastname = Vali_String::val("lastname", $req->lastname, true, 1, 150, true);
$Account->birthdate = Vali_Date::val("birthdate", $req->birthdate, false, "1900-01-01", date('Y-m-d'));
$Account->locale = Vali_String::val("locale", $req->locale, false, 2, 5, true);

$Account->setPassword(Vali_Password::val("password", $req->password, true, 8, false, ['uppercase', 'lowercase', 'number']));
$Account->create();

$Auth = new Auth_Create();
$Auth->account_id = $Account->id;
$Auth->create();

Response::success(200, "Account has been created");