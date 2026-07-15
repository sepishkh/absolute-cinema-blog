<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Http\Request;
use AbsCin\Database\DBConnection;
use AbsCin\Views\View;

abstract class BaseController {
    protected DBConnection $dbc;

    abstract protected function Init();
    public function __construct(protected Request $request) {
        $this->dbc = DBConnection::GetInstance();
        $this->Init();
    }

    public function Execute(string $template, array $vars = []) : Response {
        $view = new View($template, $vars);
        $content = $view->Render();
        return new Response($content);
    }
}
