<?php

namespace App\Controller;
require "vendor/autoload.php";

use App\Model\Model;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PerfilPermissaoController
{
    private $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function obterPerfisDaPermissao($idPermissao)
    {
        $permissoes = $this->model->getPermissoesDoPerfil($idPermissao);

        if (!$permissoes) {
            return "Permissões não encontradas para o perfil especificado";
        }
        return $permissoes;
    }

    public function obterPermissoesDoPerfil($token)
    {
        try {
            $key =
                "9b426114868f4e2179612445148c4985429e5138758ffeed5eeac1d1976e7443";
            $algoritimo = "HS256";
            $decoded = JWT::decode($token, new Key($key, $algoritimo));
            $idUsuario = $decoded->sub;

            return $this->model->getPerfilPorUsuario($idUsuario);
        } catch (\Exception $e) {
            return "Erro ao decodificar o token: " . $e->getMessage();
        }
    }
}
