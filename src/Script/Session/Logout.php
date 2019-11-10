<?php

Core::class('Auth/Session/Session_Delete');

Auth::getSession(true, true);
Session_Delete::delete();

Response::success(200, "Logout successful");