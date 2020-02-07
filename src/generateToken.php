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

    $tokenModel = new TokenModel();
    $tokenModel->setIdUser($user->getId());
    $tokenModel->setToken($token);
    $tokenModel->setExpiredAt($expiredAt);

    // The token and refresh token generated will not be saved on the database anymore
    // $tokensDAO = new TokensDAO();
    // $tokensDAO->saveToken($tokenModel);

    return [ 'token' => $token ];
}
