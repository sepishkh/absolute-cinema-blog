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
    $sqldb->Connect($DB_PATH);

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

function IsError($var, $submit) {
    return ($submit != NULL && ($var == NULL || $var == false));
}

function Signup($fname, $lname, $email, $pass) {
    if(!$fname || !$email || !$pass) {
        return 1;
    }
    require_once "paths.php";
    require_once "sqldb.php";
    $sqldb = new SQLDB();
    $sqldb->Connect($DB_PATH);
    $stmt = $sqldb->pdo->prepare("INSERT 
                                    INTO users 
                                    (first_name, last_name, email, password, role) 
                                    VALUES (:fname, :lname, :email, :pass, :role)");
    try {
        $stmt->execute(array(
            ":fname" => $fname,
            ":lname" => $lname,
            ":email" => $email,
            ":pass" => $pass,
            ":role" => 0
        ));
    } catch(PDOException $e) {
        return $e->getCode();
    }
    return NULL;
}

function GetRole($val) {
    switch($val) {
        case 2: return "god";
        case 1: return "admin";
        case 0: return "user";
    }
    return NULL;
}