<?php

Core::plugin('Vali/Vali_String');
Core::plugin('Vali/Vali_Boolean');

Core::class('Account/Account_Read');
Core::class('Auth/Session/Session_Update');


$req = Core::request();
$tkn = Vali_String::val("token", $req->token, true, 1);
$force = Vali_Boolean::val("force", $req->force, false);
$token = Auth::decodeToken($tkn, ENV_Auth::refresh_token_key);

$Session = new Session_Update($token->jti);
if (!$Session->checkPhrase($token->data->phrase)) throw new ApiException(403, "O1131", "(Refresh) Phrase incorrect");
if ($Session->account_id !== $token->iss) throw new ApiException(403, "O1132", "(Refresh) Session/Token issuer mismatch");

if (!$Session->keep) {
    if (!$force) throw new ApiException(403, "O1133", "(Refresh) Keep disabled");
    Auth::getSession(true, true);
    Auth::permit($Session->account_id, "owner", "admin");
}

$Account = new Account_Read($Session->account_id, true);


if ($Account->status === 'locked')
    throw new ApiException(400, "O1104", "Account locked");

else if ($Account->status === 'deleted')
    throw new ApiException(400, "O1105", "Account deleted");


$phrase = Core::randomString(20);
$Session->setPhrase($phrase)->update();

$acc_info = [
    "id" => $Account->id,
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
    $image = new Image_Read($Account->id, $Account->avatar);
    $acc_info["avatar"] = Image::getObject($image);
}

$authToken = $Session->getAuthToken(["account" => $acc_info]);
$refreshToken = $Session->getRefreshToken(["phrase" => $phrase]);

Response::data([
    "token" => $authToken,
    "refresh" => $refreshToken,
    "account" => $acc_info
]);


Response::success(200, "Refresh successful");