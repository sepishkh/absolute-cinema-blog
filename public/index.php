<!DOCTYPE html>

<?php

require_once dirname(__DIR__) . "/config/config.php";

use AbsCin\Models\PostsModel;
use AbsCin\Views\View;
use AbsCin\Http\Request;
use AbsCin\Http\Response;
use AbsCin\Http\Kernel;
use AbsCin\Routing\Router;
use AbsCin\Controllers\HomeController;
use AbsCin\Controllers\PostController;

$router = new Router();
$routes = include ROOT_PATH . "/routes/web.php";
foreach($routes as $method => $list) {
    foreach($list as $route) {
        $router->AddRoute($method, ...$route);
    }
}

$kernel = new Kernel($router);
$request = new Request();
$response = $kernel->Handle($request);
$response->Send();

/* if (isset($_GET["logout"])) Logout(); */
