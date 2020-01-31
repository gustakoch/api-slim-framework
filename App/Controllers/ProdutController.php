<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProductController {
    public function getProducts(Request $req, Response $res, array $args) {
        $res = $res->getBody()->write('Hello World');
        return $res;
    }
}
