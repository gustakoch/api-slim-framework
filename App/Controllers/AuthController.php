<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\TokensDAO;
use App\DAO\UsersDAO;
use App\Models\TokenModel;
use Firebase\JWT\JWT;

class AuthController {
    public function login(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();

        $usersDAO = new UsersDAO();
        $user = $usersDAO->getUserByEmail($data['email']);

        if (is_null($user))
            return $response->withStatus(401)->withJson(['error' => 'Usuário e/ou senha inválido. Tente novamente']);

        if (!password_verify($data['password'], $user->getPassword()))
            return $response->withStatus(401)->withJson(['error' => 'Usuário e/ou senha inválido. Tente novamente']);

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
        // $tokensDAO->saveToken($tokenModel);

        $response = $response->withJson([
            'token' => $token,
            'refresh_token' => $refreshToken
        ]);

        return $response;
    }
}
