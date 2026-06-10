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
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);
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

function NewPost($title, $intro, $body, $author_email) {
    if(!$title || !$intro || !$body || !$author_email) {
        return array(1, 0);
    }
    require_once "paths.php";
    require_once "sqldb.php";
    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);
    $stmt = $sqldb->pdo->prepare("SELECT id, role
                                    FROM users
                                    WHERE email=:email");
    $stmt->execute(array(":email" => $author_email));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user['id']) {
        return array(2, 0);
    } else {
        $stmt = $sqldb->pdo->prepare("INSERT 
                                        INTO posts
                                        (title, intro, body, author_id, created_at, approval)
                                        VALUES (:title, :intro, :body, :author_id, :created_at, :approval)");
        try {
        $stmt->execute(array(
            ":title" => $title,
            ":intro" => $intro,
            ":body" => $body,
            ":author_id" => $user['id'],
            ":created_at" => date("Y-m-d"),
            ":approval" => (($user['role'] == 2) ? 1 : 0)
        ));
        } catch(PDOException $e) {
            return array($e->getCode(), 0);
        }
        $new_id = $sqldb->pdo->lastInsertId();
        $table_name = "comment_" . $new_id;
        $stmt = $sqldb->pdo->prepare("CREATE TABLE {$table_name} (
                                            id          INTEGER PRIMARY KEY NOT NULL,
                                            comment     VARCHAR NOT NULL,
                                            author_id   INTEGER NOT NULL,
                                            approval    INTEGER NOT NULL,
                                            created_at  VARCHAR,
                                            FOREIGN KEY (author_id) REFERENCES users (id))");
        $stmt->execute();
        return array(0, $new_id);
    }
}