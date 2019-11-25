<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class("Log/Food/Food_Read");
$object = new Food_Read(Auth::$session->account->id);

if (isset($_GET['from']) && isset($_GET['to'])) {
    Core::plugin('Vali/Vali_Date');
    $from = Vali_Date::val("from", $_GET['from'], true, "1900-01-01", false);
    $to = Vali_Date::val("to", $_GET['to'], true, "1900-01-01", false);
} else if (isset($_GET['year']) && isset($_GET['week'])) {
    Core::plugin('Vali/Vali_Number');
    $dto = new DateTime();
    $dto->setISODate(
        Vali_Number::val("year", $_GET['year'], true, 1900, false), 
        Vali_Number::val("week", $_GET['week'], true, 1, 60)
    );
    $from = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $to = $dto->format('Y-m-d');
} else {
    throw new ApiException(422, "E0002", "Missing params (from/to OR year/week)");
}

$response = [];
$entries = $object->read($from, $to);
foreach ($entries as $key => $value) {
    $tmpVal = Food::formResponse($value);
    $response[$tmpVal->id] = $tmpVal;
}

Response::data($response);
Response::success(200, "Request successful handled");