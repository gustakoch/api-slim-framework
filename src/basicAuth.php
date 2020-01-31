<?php
namespace src;

use Tuupola\Middleware\HttpBasicAuthentication;

function basicAuth(): HttpBasicAuthentication {
    return new HttpBasicAuthentication([
        "users" => [
            "admin" => "admin"
        ]
    ]);
}
