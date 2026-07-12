<?php

namespace AbsCin\Http;

class Request {
    private array $server;
    private array $get;
    private array $post;
    private array $files;
    private array $cookie;
    private array $env;

    public function __construct() {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->cookie = $_COOKIE;
        $this->env = $_ENV;
    }

    public function GetMethod() {
        return strtolower($this->server["REQUEST_METHOD"]);
    }

    public function GetPath() {
        $path = $this->server["REQUEST_URI"] ?? '/';
        $pos = strpos($path, '?');
        if($pos === false) return $path;
        return substr($path, 0, $pos);
    }
}
