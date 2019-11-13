<?php

Core::class("Auth/Auth");
Auth::getSession(true, false);
Auth::permit(Auth::$session->account->id, "owner", "admin");

Core::class('Image/Image_Read');
Core::class("Template/Template_Read");
$obj = new Template_Read(Auth::$session->account->id);
$response = [];

if (isset(Router::$params[0])) {
    Core::plugin("Vali/Vali_Number");
    $item = $obj->readById(Vali_Number::val("id", Router::$params[0], true, 1, false));
    if ($item['image']) $item['image'] = Image::getObject((object) [
        "id" => $item['image'],
        "account_id" => $item['image_account_id'],
        "folder" => $item['image_folder'],
        "name" => $item['image_name'],
        "mime" => $item['image_mime']
    ]);
    $response = Template::formResponse($item);
} else {
    $items = $obj->read();
    foreach ($items as $key => $el) {
        if ($el['image']) $el['image'] = Image::getObject((object) [
            "id" => $el['image'],
            "account_id" => $el['image_account_id'],
            "folder" => $el['image_folder'],
            "name" => $el['image_name'],
            "mime" => $el['image_mime']
        ]);
        $response[$el["a_template_id"]] = Template::formResponse($el);
    }
}

Response::data($response);
Response::success(200, "Request successful handled");