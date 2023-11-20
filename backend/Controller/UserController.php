<?php
namespace App\Controller;
require "vendor/autoload.php";

use App\Controller\EnderecoController;
use App\Model\Endereco;
use App\Model\Model;
use App\Model\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use App\Cryptonita\Crypto;

class UserController
{
    private $usuarios;
    private $model;
    private $cripto;
    public function __construct()
    {
        $this->usuarios = new User();
        $this->model = new Model();
        $this->endereco = new Endereco();
        $this->cripto = new Crypto();
    }

    public function getAllUsers()
    {
        return $this->model->getUsersWithAddresses();
    }

    public function getUserById($id)
    {
        $user = $this->model->select("users", ["id" => $id]);

        if (count($user) === 1) {
            $userWithAddress = $this->model->getUserWithAddresses($id);
            if (!empty($userWithAddress)) {
                return $userWithAddress[0];
            }
        }

        return null;
    }

    public function deleteUserById($id)
    {
        $resultado = $this->getUserById($id);
        if (!$resultado) {
            return false;
        }
        return $this->model->delete("users", ["id" => $id]);
    }

    public function createUser($data)
    {
        $existingUser = $this->model->select("users", [
            "nome" => $data["nome"],
        ]);

        if (!empty($existingUser)) {
            return false; // Nome já existe, retorne false
        }

        $user = new User();
        $user->setNome($data["nome"]);
        $user->setEmail($data["email"]);
        $user->setSenha($data["senha"]);
        $user->setPerfilId("2");

        if (
            $this->model->insert("users", [
                "nome" => $user->getNome(),
                "email" => $user->getEmail(),
                "senha" => $user->getSenha(),
                "perfilid" => $user->getPerfilId(),
            ])
        ) {
            $this->endereco->setCep($data["cep"]);
            $this->endereco->setRua($data["rua"]);
            $this->endereco->setBairro($data["bairro"]);
            $this->endereco->setCidade($data["cidade"]);
            $this->endereco->setUf($data["uf"]);
            $this->endereco->setIduser($this->model->getLastInsertId());
            $enderecocontroller = new EnderecoController($this->endereco);
            if ($enderecocontroller->insert()) {
                return true;
            }
        }
        return false;
    }

    public function updateUser($id, $data)
    {
        $existingUser = $this->model->select("users", ["id" => $id]);

        if (count($existingUser) === 1) {
            $user = new User();
            $user->setNome($data["nome"]);
            $user->setEmail($data["email"]);
            $user->setSenha($data["senha"]);

            $result = $this->model->update(
                "users",
                [
                    "nome" => $user->getNome(),
                    "email" => $user->getEmail(),
                    "senha" => $user->getSenha(),
                ],
                ["id" => $id]
            );

            if ($result) {
                $enderecoData = [
                    "cep" => $data["cep"],
                    "rua" => $data["rua"],
                    "bairro" => $data["bairro"],
                    "cidade" => $data["cidade"],
                    "uf" => $data["uf"],
                ];

                $enderecoController = new EnderecoController($this->endereco);
                $enderecoController->update($id, $enderecoData);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function validarToken($token)
    {
        $key =
            "9b426114868f4e2179612445148c4985429e5138758ffeed5eeac1d1976e7443";
        $algoritimo = "HS256";
        try {
            $decoded = JWT::decode($token, new Key($key, $algoritimo));
            return [
                "status" => true,
                "message" => "Token válido!",
                "data" => $decoded,
            ];
        } catch (Exception $e) {
            return ["status" => false, "message" => "Token inválido!"];
        }
    }

    public function login($email, $senha, $lembrar)
    {
        $condicoes = ["email" => $email];
        $resultado = $this->model->select("users", $condicoes);

        $checado = $lembrar ? 60 * 12 : 3;
        if (!$resultado) {
            return ["status" => false, "message" => "Usuário não encontrado."];
        }

        $senhaHashArmazenada = $resultado[0]["senha"];

        if (!password_verify($senha, $senhaHashArmazenada)) {
            return ["status" => false, "message" => "Senha incorreta."];
        }

        $permissoes = $resultado[0]["perfilid"];
        $key =
            "9b426114868f4e2179612445148c4985429e5138758ffeed5eeac1d1976e7443";
        $algoritimo = "HS256";
        $payload = [
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => time(),
            "exp" => time() + 60 * $checado, // alterar duracao do token:30
            "sub" => $resultado[0]["id"],
        ];

        $jwt = JWT::encode($payload, $key, $algoritimo);

        return [
            "status" => true,
            "message" => "Login bem-sucedido!",
            "token" => $jwt,
            "permissoes" => $permissoes,
        ];
    }
}
