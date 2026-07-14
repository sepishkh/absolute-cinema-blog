<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Http\Request;
use AbsCin\Views\View;

class BaseController {
    public function __construct(
        private Request $request,
    ) {}

    public function Execute(string $template, array $vars = []) : Response {
        $view = new View($template, $vars);
        $content = $view->Render();
        return new Response($content);
    }
}
