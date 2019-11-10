<?php

Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Number");

Core::class("Admin/Admin_Log");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");


if (isset($_GET['identity'])) $identity = Vali_String::val("identity", $_GET['identity'], false, 1, 100);
else $identity = false;

if (isset($_GET['level'])) $level = Vali_String::val("level", $_GET['level'], false, 1, 20);
else $level = false;

if (isset($_GET['from'])) $from = Vali_Number::val("from", $_GET['from'], false, 0);
else $from = 0;

if (isset($_GET['to'])) $to = Vali_Number::val("to", $_GET['to'], false, 1);
else $to = $from + 100;


$entries = Admin_Log::read($level, $identity, $from, $to);

Response::data($entries);

Response::success(200, "Request successful handled");

