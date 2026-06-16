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
                                    (fname, lname, email, password, role, creation_date) 
                                    VALUES (:fname, :lname, :email, :pass, :role, :creation_date)");
    try {
        $stmt->execute(array(
            ":fname" => $fname,
            ":lname" => $lname,
            ":email" => $email,
            ":pass" => $pass,
            ":role" => 0,
            ":creation_date" => date("Y-m-d H:i")
        ));
    } catch(PDOException $e) {
        return $e->getCode();
    }
    return NULL;
}

function NewPost($title, $intro, $body, $author_email, $category_id) {
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
                                        (title, intro, body, author_id, creation_date, approval, category)
                                        VALUES (:title, :intro, :body, :author_id, :creation_date, :approval, :category_id)");
        try {
            $stmt->execute(array(
                ":title" => $title,
                ":intro" => $intro,
                ":body" => $body,
                ":author_id" => $user['id'],
                ":creation_date" => date("Y-m-d H:i"),
                ":approval" => (($user['role'] == 2) ? 1 : 0),
                ":category_id" => $category_id,
            ));
        } catch(PDOException $e) {
            return array($e->getCode(), 0);
        }
        $new_id = $sqldb->pdo->lastInsertId();
        $table_name = "comment_" . $new_id;
        $stmt = $sqldb->pdo->prepare("CREATE TABLE {$table_name} (
                                            id              INTEGER PRIMARY KEY NOT NULL,
                                            comment         VARCHAR NOT NULL,
                                            author_id       INTEGER NOT NULL,
                                            approval        INTEGER NOT NULL,
                                            creation_date   VARCHAR NOT NULL,
                                            FOREIGN KEY (author_id) REFERENCES users (id))");
        $stmt->execute();
        return array(0, $new_id);
    }
}

function UpdatePost($id, $title, $intro, $body, $category_id) {
    if(!$id || !$title || !$intro || !$body) {
        return array(1, 0);
    }
    require_once "paths.php";
    require_once "sqldb.php";
    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);
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
    } catch(PDOException $e) {
        return array($e->getCode(), 0);
    }
    return array(0, $id);
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
    switch($category) {
        case 0: return "Movie";
        case 1: return "TV Show";
        case 2: return "Theatre";
        default: return "ERROR";
    }
}

function GetRole($role) {
    switch($role) {
        case 2: return "god";
        case 1: return "admin";
        case 0: return "user";
        default: return "ERROR";
    }
}

function GetApproval($status) {
    switch ($status) {
        case -1: return "Disapproved";
        case 0: return "Waiting for approval";
        case 1: return "Approved";
        default: return "ERROR";
    }
}

function GetThumbnail($category) {
    switch($category) {
        case 0: return "🍿";
        case 1: return "📺";
        case 2: return "🎭";
        default: return "ERROR";
    }
}
