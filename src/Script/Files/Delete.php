<?php

Core::plugin('Vali/Vali_String');
Core::class("Image/Image_Delete");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(Auth::$session->account->id, "owner", "admin");

$imageID = Vali_String::val("ImageID", Router::$params[0], true, 30, 50, true);

$Image = new Image_Delete(Auth::$session->account->id, $imageID);
$img = Image::getObject($Image);
$Image->delete();

$dir = ENV_File::root_dir."/".ENV_File::folder."/".hash(ENV_File::hash_alg, Auth::$session->account->id)."/".$img["folder"];
$files = array_diff(scandir($dir), array('.','..'));
foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
}
rmdir($dir);


Response::success(200, "Request successful handled");