<?php

namespace AbsCin\Http;

class Response {
    public function __construct(
        private $content = "",
        private $status = 200,
        private $headers = [],
    ) {
        http_response_code($status);
    }

    public function Send() {
        echo($this->content);
    }
}
