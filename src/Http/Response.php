<?php

namespace AbsCin\Http;

class Response {
    public function __construct(
        private $content = "",
        private $status = 200,
        private $headers = [],
    ) {}

    private function SendHeaders(): void {
        http_response_code($this->status);
        foreach($this->headers as $key => $value) {
            header("$key: $value", false, $this->status);
        }
    }

    private function SendContent(): void {
        echo($this->content);
    }

    public function Send() {
        $this->SendHeaders();
        $this->SendContent();
    }
}
