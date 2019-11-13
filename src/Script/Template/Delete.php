<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::plugin("Vali/Vali_Number");
Core::class("Template/Template_Delete");
$id = Vali_Number::val("id", Router::$params[0], true, 1, false);
$obj = new Template_Delete(Auth::$session->account->id, $id);

$obj->delete();

Response::success(200, "Request successful handled");