<?php

use AbsCin\Controllers\HomeController;
use AbsCin\Controllers\PostController;
use AbsCin\Controllers\AuthController;

return [
    "get" => [
        ["/",       [HomeController::class, "Index"]],
        ["/home",   [HomeController::class, "Index"]],
        ["/view",   [PostController::class, "View"]],
        ["/new",    [PostController::class, "New"]],
        ["/edit",   [PostController::class, "Edit"]],
        ["/login",  [AuthController::class, "Login"]],
        ["/signup", [AuthController::class, "Signup"]],
    ],
    "post" => [
        ["/", [HomeController::class, "index"]],
    ]
];
