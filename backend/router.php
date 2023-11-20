<?php

namespace App;

use App\controller\UserController;
use App\controller\ProdutosController;
use App\controller\ProdutoUsuarioController;
use App\Model\User;
use App\Response\JsonResponse;

class Router
{
     private $requestMethod;
     private $uri;
     private $routes;
     private $usercontroller;
     private $produtoscontroller;
     private $produtousuariocontroller;
     public function __construct($requestMethod, $uri)
     {
         $this->requestMethod = $requestMethod;
         $this->uri = $uri;
         $this->user = new User();
         $this->usercontroller = new UserController();
         $this->produtoscontroller = new ProdutosController();
         $this->produtousuariocontroller = new ProdutoUsuarioController();
         $this->routes();
     }
     public function run()
     {
         try {
             $ponte = $this->procurarPonte();
             if ($ponte) {
                 echo $ponte();
             } else {
                 header("HTTP/1.1 404 Página não encontrada");
                 echo json_encode(["error" => "Not Found"]);
             }
         } catch (\Exception $e) {
             header("HTTP/1.1 500 Erro interno do Servidor");
             echo json_encode(["error" => $e->getMessage()]);
         }
     }
     private function routes()
     {
         header("Content-Type: application/json");
         header("Access-Control-Allow-Origin: *");
         header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
         header("Access-Control-Allow-Headers: Authorization, Content-Type");
         header("Cache-Control: no-cache, no-store, must-revalidate");
         error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
         ini_set("display_errors", 0);
         if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
             header("Access-Control-Allow-Origin: *");
             header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
             header(
                 "Access-Control-Allow-Headers: Authorization, Content-Type"
             );
             header("HTTP/1.1 200 OK");
             exit();
         }
         $this->routes = [
             "GET" => [
                 "/backend/usuarios" => function () {
                     header("HTTP/1.1 200 OK");
                     $usuarios = $this->usercontroller->getAllUsers();
                     $data = [
                         "status" => true,
                         "mensagem" => "Usuarios recuperados com sucesso",
                         "usuarios" => $usuarios,
                     ];
                     return json_encode($data);
                 },
                 "/backend/usuario/{id}" => function ($id) {
                     $usuario = $this->usercontroller->getUserById($id);
                     if (!$usuario) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Usuário  não encontrado",
                             "descricao" => "",
                             "usuario" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Usuário recuperado com sucesso",
                         "descricao" => "",
                         "usuario" => $usuario,
                     ];
                     return JsonResponse::make($data, 200);
                 },
                 "/backend/produtos" => function () {
                     header("HTTP/1.1 200 OK");
                     $produtos = $this->produtoscontroller->getAllProducts();
                     $data = [
                         "status" => true,
                         "mensagem" => "Produtos recuperados com sucesso",
                         "produtos" => $produtos,
                     ];
                     return json_encode($data);
                 },
                 "/backend/produtos/{id}" => function ($id) {
                     $produtos = $this->produtoscontroller->getProductById($id);
                     if (!$produtos) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Produto não encontrado",
                             "descricao" => "",
                             "produtos" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Produto recuperado com sucesso",
                         "descricao" => "",
                         "produtos" => $produtos,
                     ];
                     return JsonResponse::make($data, 200);
                 },
                 "/backend/produtos-por-usuario" => function () {
                     $result = $this->produtousuariocontroller->getProductsByUser();
                     return $result;
                 },
                 "/backend/validar-token" => function () {
                     $headers = getallheaders();
                     $token = $headers["Authorization"] ?? null;
                     $usuariosController = new UserController($usuario);
                     try {
                         if ($token !== null) {
                             $validationResponse = $usuariosController->validarToken(
                                 $token
                             );
                             if ($validationResponse["status"]) {
                                 $data = [
                                     "status" => true,
                                     "message" => "Token válido",
                                 ];
                                 return JsonResponse::make($data, 200);
                             } else {
                                 $errorMessage =
                                     $validationResponse["error"] ??
                                     "Token inválido";
                                 $data = [
                                     "status" => false,
                                     "message" => $errorMessage,
                                 ];
                                 return JsonResponse::make($data, 200);
                             }
                         }
                     } catch (Exception $e) {
                         $data = [
                             "status" => false,
                             "message" => "Token inválido: ",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = ["status" => false, "message" => "Token inválido"];
                     return JsonResponse::make($data, 200);
                 },
             ],
             "POST" => [
                 "/backend/usuario" => function () {
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $usuario = $this->usercontroller->createUser($body);
                     if (!$usuario) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Usuário já existe",
                             "descricao" => "",
                             "usuario" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Usuário criado com sucesso",
                         "descricao" => "",
                         "usuario" => $usuario,
                     ];
                     return JsonResponse::make($data, 201);
                 },
                 "/backend/login" => function () {
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $user = new \App\Model\User();
                     $usuariosController = new UserController($user);
                     if (isset($body["email"])) {
                         $user->setEmail($body["email"]);
                         $email = $body["email"];
                         $senha = $body["senha"];
                         $lembrar = $body["lembrar"];
                         $resultado = $usuariosController->login(
                             $email,
                             $senha,
                             $lembrar
                         );
                         if (!$resultado["status"]) {
                             $data = [
                                 "status" => $resultado["status"],
                                 "message" => $resultado["message"],
                             ];
                             return JsonResponse::make($data, 201);
                         }
                         $data = [
                             "status" => $resultado["status"],
                             "message" => $resultado["message"],
                             "token" => $resultado["token"],
                             "permissoesId" => $resultado["permissoes"],
                         ];
                         return JsonResponse::make($data, 201);
                     }
                 },
                 "/backend/produto" => function () {
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $produto = $this->produtoscontroller->createProduct($body);
                     if (!$produto) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Produto já existe",
                             "descricao" => "",
                             "produto" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Produto criado com sucesso",
                         "descricao" => "",
                         "produto" => $produto,
                     ];
                     return JsonResponse::make($data, 201);
                 },
                 "/backend/produtousuario" => function () {
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $produtousuario = $this->produtousuariocontroller->registerProductUser(
                         $body
                     );
                     if (!$produtousuario) {
                         $data = [
                             "status" => false,
                             "mensagem" =>
                                 "Erro: Um ou mais dos IDs fornecidos não foi encontrado no sistema",
                             "descricao" => "",
                             "produtousuario" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Vinculo criado com sucesso",
                         "descricao" => "",
                         "produtousuario" => $produtousuario,
                     ];
                     return JsonResponse::make($data, 201);
                 },
                 "/backend/permissao" => function () {
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $headers = getallheaders();
                     $token = $headers["authorization"] ?? null;
                     if ($token === null) {
                         $data = [
                             "status" => false,
                             "mensagem" =>
                                 "Erro: O token não foi fornecido nos cabeçalhos.",
                             "descricao" => "",
                             "permissoes" => "",
                         ];
                         return JsonResponse::make($data, 400);
                     }
                     if (!isset($body)) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Erro: O perfil não foi fornecido.",
                             "descricao" => "",
                             "permissoes" => "",
                         ];
                         return JsonResponse::make($data, 400);
                     }
                     $perfilId = $body;
                     $perfilPermissaoController = new PerfilPermissaoController();
                     $perfilUser = $perfilPermissaoController->obterPermissoesDoPerfil(
                         $token
                     );
                     if ($perfilId !== $perfilUser) {
                         $data = [
                             "status" => false,
                             "mensagem" =>
                                 "Erro: Os perfis fornecidos são incompatíveis.",
                             "descricao" => "",
                             "permissoes" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $permissoes = $perfilPermissaoController->obterPerfisDaPermissao(
                         $perfilId
                     );
                     if (!$permissoes) {
                         $data = [
                             "status" => false,
                             "mensagem" =>
                                 "Erro: Perfil ou permissões não encontrados.",
                             "descricao" => "",
                             "permissoes" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Permissões obtidas com sucesso.",
                         "descricao" => "",
                         "permissoes" => $permissoes,
                     ];
                     return JsonResponse::make($data, 200);
                 },
             ],
             "PUT" => [
                 "/backend/usuario/{id}" => function ($id) {
                     header("Content-Type: application/json");
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $usuario = $this->usercontroller->updateUser($id, $body);
                     if (!$usuario) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Usuário não encontrado",
                             "descricao" => "",
                             "usuario" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Usuário atualizado com sucesso",
                         "descricao" => "",
                         "usuario" => $id,
                     ];
                     return JsonResponse::make($data, 200);
                 },
                 "/backend/produto/{id}" => function ($id) {
                     header("Content-Type: application/json");
                     $body = json_decode(
                         file_get_contents("php://input"),
                         true
                     );
                     $produto = $this->produtoscontroller->updateProduct(
                         $id,
                         $body
                     );
                     if (!$produto) {
                         $data = [
                             "status" => false,
                             "mensagem" => "Produto não encontrado",
                             "descricao" => "",
                             "produto" => "",
                         ];
                         return JsonResponse::make($data, 200);
                     }
                     $data = [
                         "status" => true,
                         "mensagem" => "Produto atualizado com sucesso",
                         "descricao" => "",
                         "produto" => $id,
                     ];
                     return JsonResponse::make($data, 200);
                 },
             ],
             "DELETE" => [
                 "/backend/usuario/{id}" => function ($id) {
                     $success = $this->usercontroller->deleteUserById($id);
                     if ($success) {
                         $data = [
                             "status" => true,
                             "mensagem" => "Usuário deletado com sucesso",
                             "descricao" => "Usuário com ID $id foi deletado",
                         ];
                     } else {
                         $data = [
                             "status" => false,
                             "mensagem" => "Erro ao deletar o usuário",
                             "descricao" => "Ocorreu um problema ao tentar deletar o usuário com ID $id",
                         ];
                     }
                     return JsonResponse::make($data, 200);
                 },
                 "/backend/produto/{id}" => function ($id) {
                     $success = $this->produtoscontroller->deleteProductById(
                         $id
                     );
                     if ($success) {
                         $data = [
                             "status" => true,
                             "mensagem" => "Produto deletado com sucesso",
                             "descricao" => "Produto com ID $id foi deletado",
                         ];
                     } else {
                         $data = [
                             "status" => false,
                             "mensagem" => "Erro ao deletar o Produto",
                             "descricao" => "Ocorreu um problema ao tentar deletar o Produto com ID $id",
                         ];
                     }
                     return JsonResponse::make($data, 200);
                 },
             ],
         ];
     }
     private function procurarPonte()
     {
         foreach ($this->routes[$this->requestMethod] as $route => $ponte) {
             $routePattern = preg_replace("/\{.*\}/", "([^/]+)", $route);
             if (preg_match("@^$routePattern$@", $this->uri, $matches)) {
                 array_shift($matches);
                 return function () use ($ponte, $matches) {
                     return call_user_func_array($ponte, $matches);
                 };
             }
         }
         return false;
     }
 }
