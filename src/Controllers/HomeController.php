<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Views\View;

class HomeController extends BaseController {
    public function Index(): Response {
        $response = $this->Execute(
            "layouts/main", 
            [
                "cinema" => "SHIZ",
                "title" => "Hello World~~",
                "content" => (new View("home", ["next_page" => "2", "prev_page" => "1"]))->Render(),
            ]
        );
        return $response;
    }
}
