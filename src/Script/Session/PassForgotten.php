<?php

Core::plugin('Vali/Vali_String');
Core::plugin('Mail/Mail');

Core::class('Account/Account_Read');
Core::class('Auth/Session/Session_Password');


$req = Core::request();
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


$phrase = Core::randomString(20);
Session_Password::createReset($acc_id, $phrase);


$mail = new Mail();
$mail->to($Account->mail, $Account->username);
$mail->replyTo("noreply@osis.io", "Osis Demo");
$mail->subject = 'Password change request';
$template = Mail::loadTemplate("default/default");
$mail->content = Mail::render($template, [
    "{title}" => 'Password change request',
    "{footer}" => "By Osis",
    "{content}" => "
        Hello ".$Account->username.", <br/> <br/>
        Please use this code to change your password: <br/><br/>
        <b>".$phrase."</b> <br/><br/>
        Best Regards, <br/>
        Osis
    "
]);

if (ENV_Main::env === "production") $mail->send();
else if (ENV_Main::env === "test") Response::data(["phrase" => $phrase, "content"=>$mail->content]);
else if (ENV_Main::env === "local") echo $mail->content;

Response::success(200, "Code sent");