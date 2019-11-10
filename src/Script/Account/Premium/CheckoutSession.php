<?php

// TODO

Core::library("Stripe");
Core::environment("ENV_Bill");
Core::class("Auth/Auth");

Auth::getSession(true, true);

\Stripe\Stripe::setApiKey(ENV_Bill::api_key);
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'customer_email' => Auth::$session->account->mail,
    'subscription_data' => [
        'items' => [[
            'plan' => ENV_Bill::plan_id,
        ]],
    ],
    'success_url' => ENV_Bill::success_url,
    'cancel_url' => ENV_Bill::cancel_url
]);

Response::data($session);

Response::success(200, "Session has been created");