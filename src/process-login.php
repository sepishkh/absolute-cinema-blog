<?php

require_once "../config/config.php";
require_once Paths::$UTILZ;

$email = $_POST["email"];
$pass = $_POST["password"];

$logged_in = Login($email, $pass);
if ($logged_in) {
    header("Location: " . Paths::$INDEX);
} else {
    header("Location: " . Paths::$LOGIN . "?status=failed");
}
exit();
