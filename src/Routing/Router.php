<?php

namespace AbsCin\Routing;

use AbsCin\Http\Response;
use AbsCin\Http\Request;

class Router {
    public array $routes = [];

    public function AddRoute(string $method, string $path, array $callback) {
        $this->routes[$method][$path] = $callback;
    }
    public function Get(string $path, array $callback) {
        $this->AddRoute("get", $path, $callback);
    }
    public function Post(string $path, array $callback) {
        $this->AddRoute("post", $path, $callback);
    }
    public function Dispatch(Request $request): Response {
        $method = $request->GetMethod();
        $uri = $request->GetPath();
        if(!isset($this->routes[$method][$uri])) {
            return new Response("404 Not Found", 404);
        }
        [$class, $method] = $this->routes[$method][$uri];
        $controller = new $class($request);
        return $controller->$method();
    }
}
