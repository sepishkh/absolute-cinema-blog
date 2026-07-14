<?php

define("ROOT_PATH", dirname(__DIR__));

require_once ROOT_PATH . "/vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_WARNING & ~E_USER_NOTICE);
/* error_reporting(E_ALL); */

require_once "paths.php";
Paths::init();

use AbsCin\Database\DBConnection;
$opts = [
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

/* $DBCON = new DBConnection("localhost", "blog_access", "password", "abscin_blog", $opts, Paths::$INIT_SCHEMA); */
DBConnection::Init("localhost", "blog_access", "password", "abscin_blog", $opts, Paths::$INIT_SCHEMA);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once Paths::$UTILZ;
