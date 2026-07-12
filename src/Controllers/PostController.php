<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;

class PostController {
    public function View() : Response {
        $content = "<h2>Viewing book number </h2>";
        return new Response($content);
    }
}
