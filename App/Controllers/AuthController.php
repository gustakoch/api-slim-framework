<?php
namespace App\Controllers;

use function src\generateToken;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\TokensDAO;
use App\DAO\UsersDAO;
use Firebase\JWT\JWT;

/*
    CRIAR UMA MÉTODO PARA VERIFICAR SE O USUÁRIO JÁ TEM UM TOKEN CRIADO NO BANCO E SE ESTÁ ATIVO!
    CASO ESTEJA ATIVO, SETAR PARA "0" O ACTIVE E GERAR UM NOVO TOKEN.
*/

class AuthController {
    public function login(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();

        $usersDAO = new UsersDAO();
        $user = $usersDAO->getUserByEmail($data['email']);

        if (is_null($user))
            return $response->withStatus(401)->withJson(['error' => 'Usuário e/ou senha inválido. Tente novamente']);

        if (!password_verify($data['password'], $user->getPassword()))
            return $response->withStatus(401)->withJson(['error' => 'Usuário e/ou senha inválido. Tente novamente']);


        $tokenAndRefreshTokenGenerated = generateToken($user);

        $response = $response->withJson([
            'token' => $tokenAndRefreshTokenGenerated['token'],
            'refresh_token' => $tokenAndRefreshTokenGenerated['refreshToken']
        ]);

        return $response;
    }

    public function refreshToken(Request $request, Response $response, array $args): Response {
        $refreshToken = $request->getParsedBody()['refreshToken'];
        $refreshTokenDecoded = JWT::decode($refreshToken, getenv('JWT_SECRET_KEY'), ['HS256']);

        $tokenDAO = new TokensDAO();
        $refreshTokenExists = $tokenDAO->verifyRefreshToken($refreshToken);

        if (!$refreshTokenExists)
            return $response->withStatus(401)->withJson(['error' => 'Token inválido ou não encontrado']);

        $userDAO = new UsersDAO();
        $user = $userDAO->getUserByEmail($refreshTokenDecoded->email);

        if (!$user)
            return $response->withStatus(401)->withJson(['error' => 'Usuário não encontrado']);

        $tokenDAO->updateTokenStatus($refreshToken);
        $tokenAndRefreshTokenGenerated = generateToken($user);

        $response = $response->withJson([
            'token' => $tokenAndRefreshTokenGenerated['token'],
            'refresh_token' => $tokenAndRefreshTokenGenerated['refreshToken']
        ]);

        return $response;
    }

}
