<?php

Core::plugin("Vali/Vali_Number");

Core::class("Image/Image_Admin");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");

$page = Vali_Number::val("page", Router::$params[0], false, 1, false);

$from = (1 + ($page - 1) * 20) - 1;
$to = ($page * 20) - 1;

$entries = Image_Admin::readall($from, $to);

Response::data($entries);

Response::success(200, "Request successful handled");