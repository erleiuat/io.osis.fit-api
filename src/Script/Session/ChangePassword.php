<?php

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Password');

Core::class("Auth/Auth");
Core::class('Account/Account_Password');
Core::class('Auth/Session/Session_Delete');

Auth::getSession(true, true);

$req = Core::request();
$password = Vali_String::val("password", $req->password, true, 1, false);

$Account = new Account_Password(Auth::$session->account->id);
if (!$Account->verifyPassword($password)) throw new ApiException(403, "O1103", "Password incorrect");

$new = Vali_Password::val("new", $req->new, true, 8, false, ['uppercase', 'lowercase', 'number']);
$Account->setPassword($new);

$Account->changePassword();

Session_Delete::byAccountId(Auth::$session->account->id);

Response::success(200, "Password changed");