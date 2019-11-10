<?php

Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Number");

Core::class("Admin/Admin_Codes");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");

$entries = Admin_Codes::read();

Response::data($entries);

Response::success(200, "Request successful handled");

