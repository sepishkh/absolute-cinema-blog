<?php

if($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "utilz.php";

    $email = $_POST["email"];
    $pass = $_POST["password"];
    $submit = $_POST["submit"];

    $logged_in = Login($email, $pass);
    if($logged_in) {
        header("Location: index.php");
    } else {
        header("Location: login.php?status=failed");
    }
    exit;
}

?>