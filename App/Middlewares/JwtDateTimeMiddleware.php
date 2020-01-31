<?php
namespace App\Middlewares;

use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class JwtDateTimeMiddleware {
    public function __invoke(Request $request, Response $response, $next) {
        $token = $request->getAttribute('jwt');
        $expiredDate = new \DateTime($token['expired_at']);
        $now = new \DateTime();

        if ($expiredDate < $now)
            return $response->withStatus(401)->withJson(['error' => 'Token inválido']);

        $response = $next($request, $response);
        return $response;
    }
}
