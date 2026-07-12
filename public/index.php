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

/* $per_page = 6; */
/* $page_num = (int)($_GET["page"] ?? 1); */
/* $offset = ($page_num - 1) * $per_page; */

/* $dbc = $GLOBALS["DBCON"]; */
/* $pm = new PostsModel($dbc); */
/* $posts = $pm->GetPosts(null, [1], null, $per_page, $offset); */

/* $val = [ */
/*     "posts" => $posts, */
/*     "page_num" => $page_num, */
/* ]; */
/* $v = new View("../templates/home.php", $val); */
/* $v->GetPage(); */
?>

