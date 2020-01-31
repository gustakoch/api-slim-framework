<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\LojaModel;
use App\DAO\LojasDAO;

class LojaController {
    public function getLojas(Request $request, Response $response, array $args): Response {
        $lojasDAO = new LojasDAO();
        $lojas = $lojasDAO->getAllLojas();

        $response = $response->withJson($lojas);

        return $response;
    }

    public function setLoja(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();

        $lojasDAO = new LojasDAO();
        $loja = new LojaModel();

        $loja->setName($data['name']);
        $loja->setPhone($data['phone']);
        $loja->setAddress($data['address']);

        $lojasDAO->setLoja($loja);

        $response = $response->withJson(['message' => 'Loja cadastrada com sucesso!']);

        return $response;
    }

    public function updateLoja(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $id = $request->getQueryParams()['id'];

        $lojasDAO = new LojasDAO();
        $loja = new LojaModel();

        $loja->setName($data['name']);
        $loja->setPhone($data['phone']);
        $loja->setAddress($data['address']);

        $lojasDAO->updateLoja($id, $loja);

        $response = $response->withJson(['message' => 'Dados atualizados com sucesso!']);

        return $response;
    }

    public function deleteLoja(Request $request, Response $response, array $args): Response {
        $id = $request->getQueryParams()['id'];

        $lojasDAO = new LojasDAO();
        $lojasDAO->deleteLoja($id);

        $response = $response->withJson(['message' => 'Loja removida com sucesso!']);

        return $response;
    }
}
