<?php

require_once "utilz.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $pass = $_POST["password"];

    Logout();
    $rc = Signup($fname, $lname, $email, $pass);
    header("Location: signup.php?status=" . (($rc == NULL) ? "success" : $rc));
    exit();
}
