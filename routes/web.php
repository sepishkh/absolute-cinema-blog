<?php

use AbsCin\Controllers\HomeController;
use AbsCin\Controllers\PostController;
use AbsCin\Controllers\AuthController;
use AbsCin\Controllers\ProfileController;

return [
    "get" => [
        ["/",       [HomeController::class, "Index"]],
        ["/home",   [HomeController::class, "Index"]],
        ["/view",   [PostController::class, "View"]],
        ["/new",    [PostController::class, "New"]],
        ["/edit",   [PostController::class, "Edit"]],
        ["/login",  [AuthController::class, "Login"]],
        ["/signup", [AuthController::class, "Signup"]],
        ["/profile",[ProfileController::class, "Profile"]],
    ],
    "post" => [
        ["/", [HomeController::class, "index"]],
    ]
];
