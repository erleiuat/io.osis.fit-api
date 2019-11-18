<?php

Core::plugin("Vali/Vali_String");
Core::plugin("Vali/Vali_Number");

Core::class("Admin/Admin_Log");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");

if (isset($_GET['level'])) $level = Vali_String::val("level", $_GET['level'], false, 1, 20);
else throw new ApiException(422, "E0002", "Missing param (level)");

Admin_Log::delete($level);

Response::success(200, "Request successful handled");

