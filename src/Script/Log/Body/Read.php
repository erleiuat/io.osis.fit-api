<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class("Log/Body/Body_Read");
$object = new Body_Read(Auth::$session->account->id);

$response = [];
$entries = $object->read();
foreach ($entries as $key => $value) {
    $tmpVal = Body::formResponse($value);
    $response[$tmpVal->id] = $tmpVal;
}

Response::data($response);
Response::success(200, "Request successful handled");