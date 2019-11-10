<?php

Core::plugin('Mail/Mail');
Core::class("Auth/Auth");
Core::class("Account/Account_Verify");

Auth::getSession(true, true);

$Account = new Account_Verify(Auth::$session->account->id);
$Account->reset();

$phrase = Core::randomString(6);
$Account->create($phrase);


$mail = new Mail();
$mail->to(Auth::$session->account->mail, Auth::$session->account->username);
$mail->replyTo("noreply@osis.io", "Osis Demo");
$mail->subject = 'Verify your Osis Account';
$template = Mail::loadTemplate("default/default");
$mail->content = Mail::render($template, [
    "{title}" => 'Verify your Osis Account',
    "{footer}" => "By Osis",
    "{content}" => "
        Hello ".Auth::$session->account->username.", <br/> <br/>
        Please use this code to verify your Account: <br/><br/>
        <b>".$phrase."</b> <br/><br/>
        Best Regards, <br/>
        Osis
    "
]);

if (ENV_Main::env === "production") $mail->send();
else if (ENV_Main::env === "test") Response::data(["phrase" => $phrase, "content"=>$mail->content]);
else if (ENV_Main::env === "local") echo $mail->content;


Response::success(200, "Verification Code created");