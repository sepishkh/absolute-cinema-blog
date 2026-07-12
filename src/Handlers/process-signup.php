<?php

require_once dirname(__DIR__) . "/config/config.php";

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];
$pass = $_POST["password"];

Logout();
$rc = Signup($fname, $lname, $email, password_hash($pass, PASSWORD_DEFAULT));
header("Location: " . Paths::$SIGNUP . "?status=" . (($rc[0] == 0) ? "success" : $rc[0]));
exit();
