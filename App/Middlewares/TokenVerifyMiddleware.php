<?php
namespace App\Middlewares;

use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

use App\Exceptions\MyException;
use App\DAO\UsersDAO;

class TokenVerifyMiddleware {
    public function __invoke(Request $request, Response $response, $next) {
        $authorization = $request->getHeader('HTTP_AUTHORIZATION');
        $bearerToken = explode(' ', $authorization[0]);
        $token = $bearerToken[1];

        try {
            if (!$token)
                throw new MyException('Token não informado', 401);

            $tokenDecoded = JWT::decode($token, getenv('JWT_SECRET_KEY'), ['HS256']);
            if (is_null($tokenDecoded->email))
                throw new MyException('Chave email não encontrada no token', 401);

            $userDAO = new UsersDAO();
            $user = $userDAO->getUserByEmail($tokenDecoded->email);
            if (!$user)
                throw new MyException('Usuário não encontrado', 401);
        } catch (ExpiredException $e) {
            return $response->withJson([
                'error' => ExpiredException::class,
                'status' => 401,
                'userMessage' => 'Token inválido',
                'devMessage' => $e->getMessage()
            ], 401);
        } catch (SignatureInvalidException $e) {
            return $response->withJson([
                'error' => SignatureInvalidException::class,
                'status' => 401,
                'userMessage' => 'Token inválido',
                'devMessage' => $e->getMessage()
            ], 401);
        } catch (MyException $e) {
            return $response->withJson([
                'error' => MyException::class,
                'status' => 401,
                'userMessage' => 'Token inválido',
                'devMessage' => $e->getMessage()
            ], 401);
        }

        $request = $request->withAttribute('user', $user);

        $response = $next($request, $response);
        return $response;
    }

    public function customJson(string $classException, int $status, string $userMessage, string $exception) {
        return [
            'error' => $classException,
            'status' => $status,
            'userMessage' => $userMessage,
            'devMessage' => $exception
        ];
    }
}
