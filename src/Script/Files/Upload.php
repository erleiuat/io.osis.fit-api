<?php

Core::library("Bulletproof");
Core::class("Image/Image_Create");
Core::class("Auth/Auth");

Auth::getSession(true, true);
Auth::permit(Auth::$session->account->id, "owner", "admin");

$image = new Image_Create(Auth::$session->account->id);
$upload = new Bulletproof\Image($_FILES);
$upload->setDimension(ENV_File::max_width, ENV_File::max_height); 
$upload->setSize(ENV_File::min_size, ENV_File::max_size); 

if (!isset($_FILES["file"])) throw new ApiException(400, "F0100", "Upload File 'file' missing");
if (!$upload["file"]) throw new ApiException(500, "F0101", "Image 'file' not generated");

$image->setName($_FILES["file"]["name"]);
$image->folder = $upload->getName();
$image->mime = $upload->getMime();

$dir = ENV_File::root_dir."/".ENV_File::folder."/".hash(ENV_File::hash_alg, Auth::$session->account->id)."/".$image->folder;
if(!mkdir($dir, 0777, true)) throw new ApiException(500, "F0104", "Unable to create directory");

$upload->setName($image->name)->setLocation($dir);
if (!$upload->upload()) throw new ApiException(500, "F0103", "Image upload failed (origin) (".$upload->getError().")");

$image->create();


/** CREATE LAZY, SMALL AND MEDIUM COPIES OF IMAGE */

$orgMime = $upload->getMime();
if ($orgMime === "jpeg" && Image_Create::correctOrientation($upload->getFullPath())) {
    $orgWidth = $upload->getHeight();
    $orgHeight = $upload->getWidth();
} else {
    $orgWidth = $upload->getWidth();
    $orgHeight = $upload->getHeight();
}


// CREATE COPIES
$fileLazy = $upload->getLocation()."/".$upload->getName()."_lazy.".$upload->getMime();
$fileSmall = $upload->getLocation()."/".$upload->getName()."_small.".$upload->getMime();
$fileMedium = $upload->getLocation()."/".$upload->getName()."_medium.".$upload->getMime();
copy($upload->getFullPath(), $fileLazy);
copy($upload->getFullPath(), $fileSmall);
copy($upload->getFullPath(), $fileMedium);


// RESIZE LAZY IMAGE
bulletproof\utils\resize(
    $fileLazy, // image Path
    $orgMime, // image MIME
    $orgWidth, // Original width
    $orgHeight, // Original height
    300, // New width
    300, // New Height
    true, // Keep ratio?
    true, // Upsize if too small?
    false, // corp to size?
    [
        "jpg" => ["fallback" => 40],
        "png" => 9
    ]
);


// RESIZE SMALL IMAGE
bulletproof\utils\resize(
    $fileSmall, // image Path
    $orgMime, // image MIME
    $orgWidth, // Original width
    $orgHeight, // Original height
    600, // New width
    600, // New Height
    true, // Keep ratio?
    true, // Upsize if too small?
    false, // corp to size?
    [
        "jpg" => ["fallback" => 90],
        "png" => 5
    ]
);

// RESIZE MEDIUM IMAGE
bulletproof\utils\resize(
    $fileMedium, // image Path
    $orgMime, // image MIME
    $orgWidth, // Original width
    $orgHeight, // Original height
    3850, // New width
    3850, // New Height
    true, // Keep ratio?
    true, // Upsize if too small?
    false, // corp to size?
    [
        "jpg" => ["fallback" => 95],
        "png" => 1
    ]
);

Response::data(Image::getObject($image));
Response::success(200, "Image Upload successful");