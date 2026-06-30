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

function Login($email, $pass) {
    if ($email == null || $pass == null) return false;
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute([":email" => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row != null && password_verify($pass, $row["password"])) {
        $_SESSION["username"] = $email;
        return true;
    } else {
        Logout();
        return false;
    }
}

function IsError($var, $submit) {
    return ($submit != null && ($var == null || $var == false));
}

function Signup($fname, $lname, $email, $pass) {
    if (!$fname || !$email || !$pass) {
        return 1;
    }
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare("INSERT 
                                    INTO users 
                                    (fname, lname, email, password, role, creation_date) 
                                    VALUES (:fname, :lname, :email, :pass, :role, :creation_date)");
    try {
        $stmt->execute([
            ":fname" => $fname,
            ":lname" => $lname,
            ":email" => $email,
            ":pass" => $pass,
            ":role" => 0,
            ":creation_date" => date("Y-m-d H:i")
        ]);
    } catch (PDOException $e) {
        return $e->getCode();
    }
    return null;
}

function NewPost($title, $intro, $body, $author_email, $category_id) {
    if (!$title || !$intro || !$body || !$author_email) {
        return [1, 0];
    }
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare("SELECT id, role
                                    FROM users
                                    WHERE email=:email");
    $stmt->execute([":email" => $author_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user["id"]) {
        return [2, 0];
    } else {
        $stmt = $sqldb->pdo->prepare("INSERT 
                                        INTO posts
                                        (title, intro, body, author_id, creation_date, approval, category)
                                        VALUES (:title, :intro, :body, :author_id, :creation_date, :approval, :category_id)");
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
        $new_id = $sqldb->pdo->lastInsertId();
        return [0, $new_id];
    }
}

function UpdatePost($id, $title, $intro, $body, $category_id) {
    if (!$id || !$title || !$intro || !$body) {
        return [1, 0];
    }
    $sqldb = $GLOBALS["Sqldb"];
    $stmt = $sqldb->pdo->prepare("UPDATE posts
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
