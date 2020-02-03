<?php
namespace src;

use Firebase\JWT\JWT;
use App\DAO\TokensDAO;
use App\Models\UserModel;
use App\Models\TokenModel;

function generateToken(UserModel $user): array {
    $expiredAt = (new \DateTime())->modify('+1 days')->format('Y-m-d H:i:s');
    $tokenPayload = [
        'sub' => $user->getId(),
        'name' => $user->getName(),
        'email' => $user->getEmail(),
        'expired_at' =>$expiredAt
    ];

    $token = JWT::encode($tokenPayload, getenv('JWT_SECRET_KEY'));
    $refreshTokenPayload = [
        'email' => $user->getEmail(),
        'random' => uniqid()
    ];
    $refreshToken = JWT::encode($refreshTokenPayload, getenv('JWT_SECRET_KEY'));

    $tokenModel = new TokenModel();
    $tokenModel->setIdUser($user->getId());
    $tokenModel->setToken($token);
    $tokenModel->setRefreshToken($refreshToken);
    $tokenModel->setExpiredAt($expiredAt);

    $tokensDAO = new TokensDAO();
    $tokensDAO->saveToken($tokenModel);

    return [
        'token' => $token,
        'refreshToken' => $refreshToken
    ];
}
