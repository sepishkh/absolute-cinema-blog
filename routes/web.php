<?php

use AbsCin\Controllers\HomeController;
use AbsCin\Controllers\PostController;
use AbsCin\Controllers\LoginController;
use AbsCin\Controllers\SignupController;
use AbsCin\Controllers\ProfileController;

return [
    "get" => [
        ["/",       [HomeController::class, "Index"]],
        ["/home",   [HomeController::class, "Index"]],
        ["/view",   [PostController::class, "View"]],
        ["/new",    [PostController::class, "New"]],
        ["/edit",   [PostController::class, "Edit"]],
        ["/login",  [LoginController::class, "LoginPage"]],
        ["/signup", [SignupController::class, "SignupPage"]],
        ["/profile",[ProfileController::class, "Profile"]],
    ],
    "post" => [
        ["/login", [LoginController::class, "Login"]],
        ["/signup", [SignupController::class, "Signup"]],
    ]
];
