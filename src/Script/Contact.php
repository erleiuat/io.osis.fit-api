<?php

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Mail');
Core::plugin('Mail/Mail');


$req = Core::request();
$mailAddress = Vali_Mail::val("mail", $req->mail, true, 1);
$firstname = Vali_String::val("firstname", $req->firstname, true, 1, 150, true);
$lastname = Vali_String::val("lastname", $req->lastname, true, 1, 150, true);
$subject = Vali_String::val("subject", $req->subject, true, 1, 50, true);
$message = Vali_String::val("message", $req->message, true, 1, false, true);

$mail = new Mail();
$mail->to("reutlinger.elia@gmail.com", "Eli Reute");
$mail->replyTo($mailAddress, $firstname." ".$lastname);

$mail->subject = 'Osis Contact Request: "'.$subject.'"';
$template = Mail::loadTemplate("default/default");
$mail->content = Mail::render($template, [
    "{title}" => 'Osis Contact Request: "'.$subject.'"',
    "{footer}" => "By Osis",
    "{content}" => "
        There's a new Contact-Request from Osis <br/> <br/>
        From: <b>".$firstname." ".$lastname." | ".$mailAddress."</b> <br/>
        Subject: <b>".$subject."</b> <br/><br/>
        Message: <br/> <b>".$message."</b>
    "
]);

if (ENV_Main::env === "production") $mail->send();
else if (ENV_Main::env === "test") Response::data(["content"=>$mail->content]);
else if (ENV_Main::env === "local") echo $mail->content;

Response::success(200, "Message has been sent");