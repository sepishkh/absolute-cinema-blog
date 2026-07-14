<?php

use AbsCin\Controllers\HomeController;
use AbsCin\Controllers\PostController;

return [
    "get" => [
        ["/",       [HomeController::class, "Index"]],
        ["/home",   [HomeController::class, "Index"]],
        ["/view",   [PostController::class, "ViewPost"]],
    ],
    "post" => [
        ["/", [HomeController::class, "index"]],
    ]
];
