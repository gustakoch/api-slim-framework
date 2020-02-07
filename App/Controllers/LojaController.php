<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\LojaModel;
use App\DAO\LojasDAO;
use App\Exceptions\MyException;

class LojaController {
    public function getLojas(Request $request, Response $response, array $args): Response {
        $lojasDAO = new LojasDAO();
        $lojas = $lojasDAO->getAllLojas();

        $response = $response->withJson($lojas);

        return $response;
    }

    public function setLoja(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();

        try {
            if (!$data['name'])
                throw new MyException('Usuário não informou o nome', 400);

            if (!$data['phone'])
                throw new MyException('Usuário não informou o telefone', 400);

            if (!$data['address'])
                throw new MyException('Usuário não informou o endereço', 400);
        } catch (MyException $e) {
            return $response->withJson([
                'error' => MyException::class,
                'status' => 400,
                'userMessage' => 'Você deve informar o nome, telefone e endereço',
                'devMessage' => $e->getMessage()
            ], 400);
        }

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

        try {
            if (!$data['name'])
                throw new MyException('Usuário não informou o nome', 400);

            if (!$data['phone'])
                throw new MyException('Usuário não informou o telefone', 400);

            if (!$data['address'])
                throw new MyException('Usuário não informou o endereço', 400);

        } catch (MyException $e) {
            return $response->withJson([
                'error' => MyException::class,
                'status' => 400,
                'userMessage' => 'Você deve informar o nome, telefone e endereço',
                'devMessage' => $e->getMessage()
            ], 400);
        }

        $lojasDAO = new LojasDAO();
        $loja = new LojaModel();

        $loja->setName($data['name']);
        $loja->setPhone($data['phone']);
        $loja->setAddress($data['address']);

        try {
            if (!$id)
                throw new MyException('Não foi informado o id da loja', 400);

            if (!$lojasDAO->updateLoja($id, $loja))
                throw new MyException('Retorno false para edição da loja com id = ' . $id, 400);
        } catch (MyException $e) {
            return $response->withJson([
                'error' => MyException::class,
                'status' => 400,
                'userMessage' => 'Não foi possível editar a loja informada',
                'devMessage' => $e->getMessage()
            ], 400);
        }

        $response = $response->withJson(['message' => 'Dados atualizados com sucesso!']);

        return $response;
    }

    public function deleteLoja(Request $request, Response $response, array $args): Response {
        $id = $request->getQueryParams()['id'];

        $lojasDAO = new LojasDAO();

        try {
            if (!$lojasDAO->deleteLoja($id))
                throw new MyException('Retorno false para remover loja com id=' . $id, 400);
        } catch(MyException $e) {
            return $response->withJson([
                'error' => MyException::class,
                'status' => 400,
                'userMessage' => 'Não é possível remover a loja informada',
                'devMessage' => $e->getMessage()
            ], 400);
        }

        $response = $response->withJson(['message' => 'Loja removida com sucesso!']);

        return $response;
    }
}
