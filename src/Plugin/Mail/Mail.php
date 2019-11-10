<?php

Core::environment("ENV_Mail");
Core::library("PHPMailer");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {

    private $mail;
    public $subject;
    public $content;

    public function __construct($from_mail = ENV_Mail::from_mail, $from_name = ENV_Mail::from_name) {
        $this->mail = new PHPMailer(true);
        $this->mail->setFrom($from_mail, $from_name);
        $this->bcc(ENV_Mail::permanent_bcc);
    }

    public function from($mail, $name = null) {
        $this->mail->setFrom($mail, $name);
    }

    public function to($mail, $name = null) {
        $this->mail->addAddress($mail, $name);
    }

    public function replyTo($mail, $name) {
        $this->mail->addReplyTo($mail, $name);
    }

    public function cc($mail, $name = null) {
        $this->mail->addCC($mail, $name);
    }

    public function bcc($mail, $name = null) {
        $this->mail->addBCC($mail, $name);
    }

    public function send() {

        $this->mail->isHTML(true);
        $this->mail->Subject = $this->subject;
        $this->mail->Body = $this->content;

        try { 
            $this->mail->send();
        } catch (\Exception $e) {
            throw new ApiException(500, "M0901", "Unable to send Mail");
        }

    }

    public static function loadTemplate($name) {
        ob_start();
        require("Template/".$name.".html");
        return ob_get_clean();
    }

    public static function render($template, $content) {
        return str_replace(array_keys($content), array_values($content), $template);
    }

}