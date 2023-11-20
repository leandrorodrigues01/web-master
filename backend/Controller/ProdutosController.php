<?php
namespace App\Controller;

use App\Model\Model;
use App\Model\Produtos;

class ProdutosController
{
    private $produtos;
    private $model;

    public function __construct()
    {
        $this->produtos = new Produtos();
        $this->model = new Model();
    }

    public function getAllProducts()
    {
        return $this->model->select("produtos");
    }

    public function createProduct($data)
    {
        $existingProduct = $this->model->select("produtos", [
            "nome" => $data["nome"],
        ]);
        if (!empty($existingProduct)) {
            return false; // Nome já existe, retorna false
        }
        $produto = new Produtos();
        $produto->setNome($data["nome"]);
        $produto->setPreco($data["preco"]);
        $produto->setQuantidade($data["quantidade"]);

        return $this->model->insert("produtos", [
            "nome" => $produto->getNome(),
            "preco" => $produto->getPreco(),
            "quantidade" => $produto->getQuantidade(),
        ]);
    }

    public function getProductById($id)
    {
        $produto = $this->model->select("produtos", ["id" => $id]);
        if (count($produto) === 1) {
            return $produto[0];
        } else {
            return null;
        }
    }

    public function deleteProductById($id)
    {
        $resultado = $this->getProductById($id);
        if (!$resultado) {
            return false;
        }
        return $this->model->delete("produtos", ["id" => $id]);
    }

    public function updateProduct($id, $data)
    {
        $existingProduct = $this->model->select("produtos", ["id" => $id]);

        if (count($existingProduct) === 1) {
            $produto = new Produtos();
            $produto->setNome($data["nome"]);
            $produto->setPreco($data["preco"]);
            $produto->setQuantidade($data["quantidade"]);

            $result = $this->model->update(
                "produtos",
                [
                    "nome" => $produto->getNome(),
                    "preco" => $produto->getPreco(),
                    "quantidade" => $produto->getQuantidade(),
                ],
                ["id" => $id]
            );

            return $result;
        } else {
            return false;
        }
    }
}
