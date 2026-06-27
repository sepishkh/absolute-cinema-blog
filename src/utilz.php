<!DOCTYPE html>

<?php

require_once "../config/config.php";

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
    $sqldb = $GLOBALS["Sqldb"];
    $users = $sqldb->pdo->prepare("SELECT * FROM users WHERE email=:email");
    $users->execute([":email" => $email]);
    $user = $users->fetch(PDO::FETCH_ASSOC);
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
        return 1;
    }
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare(
        "INSERT 
        INTO users 
        (fname, lname, email, password, role, creation_date) 
        VALUES (:fname, :lname, :email, :pass, :role, :creation_date)"
    );
    $stmt->execute([
        ":fname" => $fname,
        ":lname" => $lname,
        ":email" => $email,
        ":pass" => $pass,
        ":role" => 0,
        ":creation_date" => date("Y-m-d H:i")
    ]);
    return null;
}

function NewPost($title, $intro, $body, $author_email, $category_id) {
    if (IsEmpty($title) || IsEmpty($intro) || IsEmpty($body) || IsEmpty($author_email)) {
        return [1, 0];
    }
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare(
        "SELECT id, role
        FROM users
        WHERE email=:email"
    );
    $stmt->execute([":email" => $author_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user == null) return [2, 0];
    $stmt = $sqldb->pdo->prepare(
        "INSERT 
        INTO posts
        (title, intro, body, author_id, creation_date, approval, category)
        VALUES (:title, :intro, :body, :author_id, :creation_date, :approval, :category_id)"
    );
    try {
        $stmt->execute([
            ":title" => $title,
            ":intro" => $intro,
            ":body" => $body,
            ":author_id" => $user["id"],
            ":creation_date" => date("Y-m-d H:i"),
            ":approval" => (($user["role"] == 2) ? 1 : 0),
            ":category_id" => $category_id,
        ]);
    } catch (PDOException $e) {
        return [$e->getCode(), 0];
    }
    $post_id = $sqldb->pdo->lastInsertId();
    $cmnt_table = CommentTable($post_id);
    $stmt = $sqldb->pdo->prepare(
        "CREATE TABLE $cmnt_table (
            id              INTEGER PRIMARY KEY NOT NULL,
            comment         VARCHAR NOT NULL,
            author_id       INTEGER NOT NULL,
            approval        INTEGER NOT NULL,
            creation_date   VARCHAR NOT NULL,
        )"
    );
    $stmt->execute();
    return [0, $post_id];
}

function UpdatePost($id, $title, $intro, $body, $category_id) {
    if (IsEmpty($id) || IsEmpty($title) || IsEmpty($intro) || IsEmpty($body)) {
        return [1, 0];
    }
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare(
        "UPDATE posts
        SET title=:title,
            intro=:intro,
            body=:body,
            category=:category
        WHERE id=:id");
    try {
        $stmt->execute([
            ":title" => $title,
            ":intro" => $intro,
            ":body" => $body,
            ":category" => $category_id,
            ":id" => $id
        ]);
    } catch (PDOException $e) {
        return [$e->getCode(), 0];
    }
    return [0, $id];
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
