<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class("Log/Activity/Activity_Delete");
$object = new Activity_Delete(Auth::$session->account->id);

$object->set($object->readById(Router::$params[0]));
$object->delete();

Response::success(200, "Request successful handled");