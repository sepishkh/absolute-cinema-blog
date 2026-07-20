<?php

namespace AbsCin\Http;

class Request {
    public function __construct(
        private readonly array $query,
        private readonly array $request,
        private readonly array $cookies,
        private readonly array $files,
        private readonly array $server,
    ) {
        /* $this->get = []; */
        /* $this->post = []; */
        /* foreach($_GET as $key => $value) { */
        /*     $this->get[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS); */
        /* } */
        /* foreach($_POST as $key => $value) { */
        /*     $this->post[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); */
        /* } */

        /* $this->get = $_GET; */
        /* $this->post = $_POST; */
        /* $this->server = $_SERVER; */
        /* $this->files = $_FILES; */
        /* $this->cookie = $_COOKIE; */
        /* $this->env = $_ENV; */
    }

    public static function CreateFromGlobals(): self {
        return new self(
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public function GetMethod(): string {
        return strtolower($this->server["REQUEST_METHOD"] ?? "get");
    }

    public function GetPath(): string {
        $path = $this->server["REQUEST_URI"] ?? '/';
        $pos = strpos($path, '?');
        return (($pos === false) ? $path : substr($path, 0, $pos));
    }

    public function GetKey(string $key, mixed $default = null): ?string {
        return $this->query[$key] ?? $default;
    }

    public function Get(): array {
        return $this->query;
    }

    public function Post(): array {
        return $this->request;
    }
}
