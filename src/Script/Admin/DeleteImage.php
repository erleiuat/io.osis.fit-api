<?php

Core::plugin("Vali/Vali_String");

Core::class("Image/Image_Admin");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(null, "admin");

$imageID = Vali_String::val("imageID", Router::$params[0], true, 5, 260, true);

$img = Image_Admin::readObject($imageID);

try {
    $dir = ENV_File::root_dir."/".ENV_File::folder."/".hash(ENV_File::hash_alg, $img["account_id"])."/".$img["folder"];
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    rmdir($dir);
} catch (Exception $e) {
    print_r($e);
}

Image_Admin::delete($imageID);

Response::success(200, "Request successful handled");