<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class("Log/Food/Food_Delete");
$food = new Food_Delete(Auth::$session->account->id);

$food->set($food->readById(Router::$params[0]));
$food->delete();

Response::success(200, "Request successful handled");