<!DOCTYPE html>

<?php

require_once dirname(__DIR__) . "/config/config.php";

use AbsCin\Models\PostsModel;
use AbsCin\Models\UsersModel;

function GetUsername() {
    return IsLoggedIn() ? $_SESSION["username"] : null;
}

function IsLoggedIn() {
    return isset($_SESSION["username"]);
}

function Logout() {
    unset($_SESSION["username"]);
}

function IsEmpty($var) {
    return ($var == NULL || empty(trim($var)));
}

function NotEmpty($var) {
    return !IsEmpty($var);
}

function Login($email, $pass) {
    if (IsEmpty($email) || IsEmpty($pass)) return false;
    $dbc = $GLOBALS["DBCON"];
    $um = new UsersModel($dbc);
    $user = $um->GetUserByEmail($email)->fetch();
    if ($user != null && password_verify($pass, $user["password"])) {
        $_SESSION["username"] = $email;
        return true;
    } else {
        Logout();
        return false;
    }
}

function Signup($fname, $lname, $email, $pass) {
    if (IsEmpty($fname) || IsEmpty($email) || IsEmpty($pass)) {
        return null;
    }
    $dbc = $GLOBALS["DBCON"];
    $um = new UsersModel($dbc);
    $res = $um->InsertUser($fname, $lname, $email, $pass, 0, date("Y-m-d H:i"));
    return $res;
}

function NewPost($title, $intro, $body, $author_email, $category_id) {
    if (IsEmpty($title) || IsEmpty($intro) || IsEmpty($body) || IsEmpty($author_email)) {
        return [1, 0];
    }
    $dbc = $GLOBALS["DBCON"];
    $pm = new PostsModel($dbc);
    $um = new UsersModel($dbc);
    $user = $um->GetUserByEmail($author_email)->fetch();
    if ($user == null) return [2, 0];
    $res = $pm->InsertPost($title, $intro, $body, $user["id"], date("Y-m-d H:i"), (($user["role"] == 2) ? 1 : 0), $category_id);
    return $res;
}

function UpdatePost($id, $title, $intro, $body, $category_id) {
    if (IsEmpty($id) || IsEmpty($title) || IsEmpty($intro) || IsEmpty($body)) {
        return [1, 0];
    }
    $dbc = $GLOBALS["DBCON"];
    $pm = new PostsModel($dbc);
    $res = $pm->UpdatePost($id, $title, $intro, $body, $category_id);
    return [$res, $id];
}

function IsActive($page) {
    $current = basename($_SERVER["SCRIPT_NAME"]);
    return (($page === $current) ? "active" : "");
}

