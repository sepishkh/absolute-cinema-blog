<!DOCTYPE html>

<?php

session_start();

function GetUsername() {
    return IsLoggedIn() ? $_SESSION["username"] : NULL;
}

function IsLoggedIn() {
    return isset($_SESSION["username"]);
}

function Logout() {
    unset ($_SESSION["username"]);
}

function Login($email, $pass) {
    if($email == NULL || $pass == NULL) return false;
    require_once "paths.php";
    require_once "sqldb.php";

    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

    $stmt = $sqldb->pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(array(":email" => $email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row != NULL && $row["password"] == $pass) {
        $_SESSION["username"] = $email;
        return true;
    } else {
        Logout();
        return false;
    }
}