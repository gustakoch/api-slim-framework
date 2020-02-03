<?php

use function src\{
    slimConfiguration,
    basicAuth,
    jwtAuth
};
use App\Controllers\{
    AuthController,
    ProductController,
    LojaController
};
use App\Middlewares\JwtDateTimeMiddleware;

$app = new \Slim\App(slimConfiguration());

$app->post('/login', AuthController::class . ':login');
$app->post('/refresh', AuthController::class . ':refreshToken');

$app->get('/teste', function() {
    echo "Deu boa!";
})->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/', function($request, $response, $args) {
    return $response->withJson(["message" => "It's Working"]);
})->add(basicAuth());

$app->get('/loja', LojaController::class . ':getLojas');
$app->post('/loja', LojaController::class . ':setLoja');
$app->put('/loja', LojaController::class . ':updateLoja');
$app->delete('/loja', LojaController::class . ':deleteLoja');

$app->get('/product', ProductController::class . ':getProducts');
$app->post('/product', ProductController::class . ':setProduct');
$app->put('/product', ProductController::class . ':updateProduct');
$app->delete('/product', ProductController::class . ':deleteProduct');

/* Aplicando middleware em todas as rotas

    $app->group('', function() use ($app) {
        $app->get('/loja', LojaController::class . ':getLojas');
        $app->post('/loja', LojaController::class . ':setLoja');
        $app->put('/loja', LojaController::class . ':updateLoja');
        $app->delete('/loja', LojaController::class . ':deleteLoja');

        $app->get('/product', ProductController::class . ':getProducts');
        $app->post('/product', ProductController::class . ':setProduct');
        $app->put('/product', ProductController::class . ':updateProduct');
        $app->delete('/product', ProductController::class . ':deleteProduct');
    })->add(basicAuth());

*/

$app->run();
