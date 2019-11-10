<?php

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Password');

Core::class('Account/Account_Read');
Core::class('Auth/Session/Session_Password');
Core::class('Account/Account_Password');
Core::class('Auth/Session/Session_Delete');

$req = Core::request();
$code = Vali_String::val("code", $req->code, true, 1, 100);
$password = Vali_Password::val("password", $req->password, true, 8, false, ['uppercase', 'lowercase', 'number']);
$identifier = Vali_String::val("identifier", $req->identifier, true, 1, false);

if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    $acc_id = Account::getIdByMail($identifier);
    if (!$acc_id) throw new ApiException(400, "A1021", "Mail not found");
} else {
    $acc_id = Account::getIdByUsername($identifier);
    if (!$acc_id) throw new ApiException(400, "A1022", "Username not found");
}

$Account = new Account_Read($acc_id, true);

if ($Account->status === 'locked')
    throw new ApiException(403, "O1104", "Account locked");

else if ($Account->status === 'deleted')
    throw new ApiException(403, "O1105", "Account deleted");

if (Session_Password::verifyReset($acc_id, $code)) {

    $Account = new Account_Password($acc_id);

    $Account->setPassword($password);
    $Account->changePassword();

    Session_Delete::byAccountId($acc_id);

    Response::success(200, "Password changed");

} else {
    throw new ApiException(403, "O1144", "Code invalid");
}
