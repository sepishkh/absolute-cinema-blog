<?php


require_once dirname(__DIR__) . "/config/config.php";

$email = $_POST["email"];
$pass = $_POST["password"];

$logged_in = Login($email, $pass);
if ($logged_in) {
    header("Location: " . Paths::$INDEX);
} else {
    header("Location: " . Paths::$LOGIN . "?status=failed");
}
exit();
