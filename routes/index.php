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
use App\Middlewares\TokenVerifyMiddleware;

$app = new \Slim\App(slimConfiguration());

$app->get('/', function($request, $response, $args) {
    return $response->withJson(["message" => "It's Working"]);
});
$app->post('/login', AuthController::class . ':login');

$app->group('', function() use ($app) {
    $app->get('/refresh', AuthController::class . ':refreshToken');

    $app->get('/loja', LojaController::class . ':getLojas');
    $app->post('/loja', LojaController::class . ':setLoja');
    $app->put('/loja', LojaController::class . ':updateLoja');
    $app->delete('/loja', LojaController::class . ':deleteLoja');

    $app->get('/product', ProductController::class . ':getProducts');
    $app->post('/product', ProductController::class . ':setProduct');
    $app->put('/product', ProductController::class . ':updateProduct');
    $app->delete('/product', ProductController::class . ':deleteProduct');
})->add(new TokenVerifyMiddleware());

$app->run();
