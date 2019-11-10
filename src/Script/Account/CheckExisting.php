<?php

Core::plugin('Vali/Vali_String');
Core::class('Account/Account');

$identifier = Vali_String::val("identifier", $_GET['identifier'], true, 1, false);
$Account = new Account();
$found = false;

if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    if (Account::getIdByMail($identifier)) $found = true;
} else {
    if (Account::getIdByUsername($identifier)) $found = true;
}

Response::data([
    "found" => $found
]);

Response::success(200, "Check successful");