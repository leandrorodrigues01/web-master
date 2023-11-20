<?php

namespace App;
require __DIR__ . '/../vendor/autoload.php';
 
use App\router;


$requestMethod = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router = new Router($requestMethod, $uri);
$router->run();