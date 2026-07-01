<!DOCTYPE html>

<?php

require_once "../config/config.php";
require_once Paths::$POSTS_MODEL;
require_once Paths::$USERS_MODEL;

session_start();

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

function CommentTable($post_id) {
    return "comment_" . $post_id;
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
    $cmnt_table = CommentTable($res[1]);
    $stmt = $dbc->pdo->prepare(
        "CREATE TABLE $cmnt_table (
            id              INT AUTO_INCREMENT PRIMARY KEY,
            comment         VARCHAR(255) NOT NULL,
            author_id       INT NOT NULL,
            approval        INT NOT NULL,
            creation_date   VARCHAR(255) NOT NULL
        )"
    );
    $stmt->execute();
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

function Escape($text) {
    return htmlspecialchars($text, ENT_HTML5, "UTF-8");
}

function FormatDate($date) {
    $d = strtotime($date);
    return date("M d, Y", $d);
}

function FullName($fname, $lname) {
    return Escape($fname . " " . $lname);
}

function GetCategory($category) {
    switch ($category) {
        case 0:
            return "Movie";
        case 1:
            return "TV Show";
        case 2:
            return "Theatre";
        default:
            return "ERROR";
    }
}

function GetRole($role) {
    switch ($role) {
        case 2:
            return "god";
        case 1:
            return "admin";
        case 0:
            return "user";
        default:
            return "ERROR";
    }
}

function GetApproval($status) {
    switch ($status) {
        case -1:
            return "Disapproved";
        case 0:
            return "Waiting for approval";
        case 1:
            return "Approved";
        default:
            return "ERROR";
    }
}

function GetThumbnail($category) {
    switch ($category) {
        case 0:
            return "🍿";
        case 1:
            return "📺";
        case 2:
            return "🎭";
        default:
            return "ERROR";
    }
}
