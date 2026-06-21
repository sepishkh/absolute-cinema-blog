<?php

require_once "../config/config.php";
require_once Paths::$UTILZ;

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];
$pass = $_POST["password"];

Logout();
$rc = Signup($fname, $lname, $email, password_hash($pass, PASSWORD_DEFAULT));
header("Location: " . Paths::$SIGNUP . "?status=" . (($rc == NULL) ? "success" : $rc));
exit();
