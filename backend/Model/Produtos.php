<?php
namespace App\Model;


class Produtos {
    private $id;
    private $nome;
    private $preco;
    private $quantidade;
    private $criado;
    public function __construct() {
      
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }


    public function getPreco() {
      return $this->preco;
  }

  public function setPreco($preco) {
      $this->preco = $preco;
  }

  public function getQuantidade() {
    return $this->quantidade;
}

public function setQuantidade($quantidade) {
    $this->quantidade = $quantidade;
}


public function getCriado() {
  return $this->criado;
}

public function setCriado($criado) {
  $this->criado = $criado;
}

public function getType() {
  return 'Produtos';
}

    public function toArray() {
        return ['id' => $this->getId(), 'nome' => $this->getNome(),  'preco' => $this->getPreco(), 'quantidade' => $this->getQuantidade(),'criado' => $this->getCriado(),  'type' => $this->getType()];
    }
}