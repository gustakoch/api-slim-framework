<?php
namespace App\Controllers;

use function src\generateToken;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Exceptions\MyException;
use App\DAO\UsersDAO;

class AuthController {
    public function login(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $email = $data['email'];
        $password = $data['password'];

        $usersDAO = new UsersDAO();
        $user = $usersDAO->getUserByEmail($email);

        try {
            if (is_null($user))
                throw new MyException('E-mail não encontrado', 400);

            if (!password_verify($password, $user->getPassword()))
                throw new MyException('A senha informada está incorreta', 400);
        } catch (MyException $e) {
            return $response->withJson([
                'error' => MyException::class,
                'status' => 400,
                'userMessage' => 'Usuário ou senha inválida! Tente novamente.',
                'devMessage' => $e->getMessage()
            ], 400);
        }

        $generated = generateToken($user);
        $response = $response->withJson(['token' => $generated['token']]);

        return $response;
    }

    public function refreshToken(Request $request, Response $response, array $args): Response {
        $user = $request->getAttribute('user');

        $generated = generateToken($user);
        $response = $response->withJson(['token' => $generated['token']]);

        return $response;
    }

}
