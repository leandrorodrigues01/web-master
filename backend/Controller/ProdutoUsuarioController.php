<?php
namespace App\Controller;

use App\Controller\ProdutosController;
use App\Controller\UserController;
use App\Model\Model;
use App\Model\User;
use App\Model\Produtos;
use App\Model\ProdutoUsuario;

use App\Response\JsonResponse;

class ProdutoUsuarioController
{
    private $model;

    public function __construct()
    {
        $this->ProdutoUsuario = new ProdutoUsuario();

        $this->model = new Model();

        $this->usuarioController = new UserController();
        $this->produtoController = new ProdutosController();
    }

    public function registerProductUser($data)
    {
        $usuario = $this->usuarioController->getUserById(
            $data["funcionarioId"]
        );
        $produto = $this->produtoController->getProductById($data["produtoId"]);

        if ($usuario !== null && $produto !== null) {
            $inserido = $this->model->insert("produto_usuario", [
                "id_usuario" => $data["funcionarioId"],
                "id_produto" => $data["produtoId"],
            ]);

            if ($inserido) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    public function getProductsByUser()
    {
        $result = $this->model->select("produtos_adicionados_por_usuario", []);
        return JsonResponse::make($result, 200);
    }
}
