<?php

namespace AbsCin\Http;

use AbsCin\Routing\Router;

class Kernel {
    public function __construct(
        private Router $router
    ) {}

    public function Handle(Request $request): Response {
        $response = $this->router->Dispatch($request);
        return $response;
    }
}
