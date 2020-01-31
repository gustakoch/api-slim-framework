<?php
namespace src;

use Tuupola\Middleware\JwtAuthentication;

function jwtAuth(): JwtAuthentication {
    return new JwtAuthentication([
        'secret' => getenv('JWT_SECRET_KEY'), // verify if the secret key is correct
        'attribute' => 'jwt' // send the jwt token to the next middleware
    ]);
}
