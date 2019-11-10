<?php

class ENV_Routes {

    const v1 = [

        "ping.post" => [
            "process" => 1.0,
            "method" => "POST",
            "name" => "ping",
            "description" => "ping",
            "script" => "@scripts@/Ping"
        ],
    
        "session.post" => [
            "process" => 2.0,
            "method" => "POST",
            "name" => "create session",
            "description" => "create session (login)",
            "script" => "@scripts@/Session/Login"
        ],
    
        "session.put" => [
            "process" => 2.1,
            "method" => "PUT",
            "name" => "refresh session",
            "description" => "refresh session (refresh login)",
            "script" => "@scripts@/Session/Refresh"
        ],
    
        "session.delete" => [
            "process" => 2.2,
            "method" => "DELETE",
            "name" => "delete session",
            "description" => "delete session (logout)",
            "script" => "@scripts@/Session/Logout"
        ],

        "account.post" => [
            "process" => 3.0,
            "method" => "POST",
            "name" => "create account",
            "description" => "create/register new account",
            "script" => "@scripts@/Account/Create"
        ],

        "account.*.get" => [
            "process" => 3.1,
            "method" => "GET",
            "name" => "get account data",
            "description" => "get account data",
            "script" => "@scripts@/Account/Read"
        ],

        "account.*.put" => [
            "process" => 3.2,
            "method" => "PUT",
            "name" => "update account data",
            "description" => "update account data",
            "script" => "@scripts@/Account/Update"
        ]

    ];

}