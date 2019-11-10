<?php

Core::plugin("Vali/Vali_String");
Core::class("Account/Account_Verify");

if (isset($_GET['mail'])) {
    Core::plugin("Vali/Vali_Mail");
    $mail = Vali_Mail::val("mail", $_GET['mail'], true, 5, 90);
    $acc_id = Account_Verify::getIdByMail($mail);
} else {
    Core::class("Auth/Auth");
    Auth::getSession(true, true);
    $acc_id = Auth::$session->account->id;
}

$Account = new Account_Verify($acc_id);
$code = Vali_String::val("code", $_GET['code'], true, 5, 10);

if ($Account->verify($code)) Response::success(200, "Account has been verified");
else Response::error(400, 8801, "Verification failed");