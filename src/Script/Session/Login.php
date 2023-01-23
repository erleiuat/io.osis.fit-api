<?php

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Boolean');

Core::class('Account/Account_Read');
Core::class('Auth/Session/Session_Create');


$req = Core::request();
$identifier = Vali_String::val("identifier", $req->identifier, true, 1, false);
$password = Vali_String::val("password", $req->password, true, 1, false);
$keep = Vali_Boolean::val("keep", $req->keep);

if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
  $acc_id = Account::getIdByMail($identifier);
  if (!$acc_id) throw new ApiException(400, "A1021", "Mail not found");
} else {
  $acc_id = Account::getIdByUsername($identifier);
  if (!$acc_id) throw new ApiException(400, "A1022", "Username not found");
}

$Account = new Account_Read($acc_id, true);
if (!$Account->verifyPassword($password)) throw new ApiException(403, "O1103", "Password incorrect");

if ($Account->status === 'locked')
  throw new ApiException(403, "O1104", "Account locked");

else if ($Account->status === 'deleted')
  throw new ApiException(403, "O1105", "Account deleted");

$phrase = Core::randomString(20);
$Session = new Session_Create($acc_id, $phrase, $keep ? 1 : 0);
$Session->create();

$acc_info = [
  "id" => $acc_id,
  "mail" => $Account->mail,
  "username" => $Account->username,
  "level" => $Account->level,
  "status" => $Account->status,
  "firstname" => $Account->firstname,
  "lastname" => $Account->lastname,
  "birthdate" => $Account->birthdate,
  "locale" => $Account->locale,
  "avatar" => $Account->avatar
];

if ($Account->avatar) {
  Core::class('Image/Image_Read');
  $image = new Image_Read($acc_id, $Account->avatar);
  $acc_info["avatar"] = Image::getObject($image);
}

$authToken = $Session->getAuthToken(["account" => $acc_info]);
$refreshToken = $Session->getRefreshToken(["phrase" => $phrase]);

Response::data([
  "token" => $authToken,
  "refresh" => $refreshToken,
  "account" => $acc_info
]);

Response::success(200, "Login successful");
