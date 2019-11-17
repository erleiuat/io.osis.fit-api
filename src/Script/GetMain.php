<?php

Core::class("Auth/Auth");
Auth::getSession(true, true);

Core::plugin("Vali/Vali_String");
$id = Vali_String::val("accountID", Router::$params[0], true, 40, 80, true);
Auth::permit($id, "owner", "admin");


if (isset($_GET['from']) && isset($_GET['to'])) {
    Core::plugin('Vali/Vali_Date');
    $from = Vali_Date::val("from", $_GET['from'], true, "1900-01-01", date('Y-m-d'));
    $to = Vali_Date::val("to", $_GET['to'], true, "1900-01-01", date('Y-m-d'));
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


Core::class("Destiny/Goals/Goals_Read");
$obj1 = new Goals_Read($id);
$obj1->read();

Core::class("Destiny/Metabolism/Metabolism_Read");
$obj2 = new Metabolism_Read($id);
$obj2->read();


Core::class("Log/Body/Body_Read");
$obj3 = new Body_Read($id);
$entries = $obj3->read();

$body = [];
foreach ($entries as $key => $value) {
    $tmpVal = Body::formResponse($value);
    $body[$tmpVal->id] = $tmpVal;
}


Core::class("Log/Food/Food_Read");
$obj4 = new Food_Read($id);
$entries = $obj4->read($from, $to);

$food = [];
foreach ($entries as $key => $value) {
    $tmpVal = Food::formResponse($value);
    $food[$tmpVal->id] = $tmpVal;
}


Core::class("Log/Activity/Activity_Read");
$obj5 = new Activity_Read($id);
$entries = $obj5->read($from, $to);

$activity = [];
foreach ($entries as $key => $value) {
    $tmpVal = Activity::formResponse($value);
    $activity[$tmpVal->id] = $tmpVal;
}


Response::data([
    "destiny" => [
        "goals" => [
            "weight" => $obj1->weight ? (double) $obj1->weight : null,
            "fat" => $obj1->fat ? (double) $obj1->fat : null,
            "date" => $obj1->date
        ],
        "metabolism" => [
            "gender" => $obj2->gender,
            "height" => $obj2->height ? (double) $obj2->height : null,
            "birthdate" => $obj2->birthdate,
            "pal" => $obj2->pal ? (double) $obj2->pal : null
        ]
    ],
    "body" => $body,
    "food" => $food,
    "activity" => $activity
]);

Response::success(200, "Request successful handled");