<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_WARNING & ~E_USER_NOTICE);

require_once "paths.php";
Paths::init();

require_once Paths::$DBCONNECTION;
$DBCON = new DBConnection("localhost", "blog_access", "password", "abscin_blog", Paths::$INIT_SCHEMA);
