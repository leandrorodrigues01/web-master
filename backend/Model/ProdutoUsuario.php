<?php

namespace App\Model;

class ProdutoUsuario {
    private $id;
    private $id_usuario;
    private $id_produto;
    private $data_cadastro;
 

  
    public function __construct() {
      
    }


        public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getIdProduto() {
        return $this->id_produto;
    }

    public function setIdProduto($id_produto) {
        $this->id_produto = $id_produto;
    }

    public function getDataCadastro() {
        return $this->data_cadastro;
    }

    public function setDataCadastro($data_cadastro) {
        $this->data_cadastro = $data_cadastro;
    }
}

 
 