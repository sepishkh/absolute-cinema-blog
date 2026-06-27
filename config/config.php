<?php

require_once "paths.php";
Paths::init();

require_once Paths::$SQLDB;
$Sqldb = new SQLDB();
$Sqldb->StartDBConnection(Paths::$DB, Paths::$SCHEMA);

?>