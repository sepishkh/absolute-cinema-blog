<!DOCTYPE html>

<?php

require_once "utilz.php";

$id = $_GET['id'];
if(!IsLoggedIn() || $id == NULL || empty(trim($id))) {
    include "404.php";
    exit;
}

require_once "paths.php";
require_once "sqldb.php";

$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);
$stmt = $sqldb->pdo->prepare("SELECT *
                            FROM posts
                            WHERE id=:view_id");
$stmt->execute([":view_id" => $id]);
$content = $stmt->fetch(PDO::FETCH_ASSOC);
if($content == NULL) {
    include "404.php";
    exit;
}

$submit = $_POST["submit"];
$title = $content["title"];
$intro = $content["intro"];
$body = $content["body"];

if ($submit) {
    $title = $_POST["title"];
    $intro = $_POST["intro"];
    $body = $_POST["body"];
    $stmt = $sqldb->pdo->prepare("SELECT * 
                                FROM users
                                WHERE email=:email");
    $stmt->execute(array(":email" => GetUsername()));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $sqldb->pdo->prepare("UPDATE posts
                            SET title=:title,
                                intro=:intro,
                                body=:body,
                                author_id=:author_id
                            WHERE id=:id");
    $stmt->execute([
        ":title" => $title,
        ":intro" => $intro,
        ":body" => $body,
        ":author_id" => $user["id"],
        ":id" => $id
    ]);
    header("Location: view.php?view=" . $id);
    exit;
}


?>


<html>
    <head>
        <title>Edit Post</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <?php require_once "header.php" ?>
        </header>
        <?php if(!IsLoggedIn()) : ?>
            <h1> Please <a href="login.php">Login</a> first.</h1>
        <?php exit(); ?>
        <?php endif ?>
        <p></p>
        <form action="edit.php?id=<?= $id ?>" method="post">
            Title:
                <textarea name="title" rows="1" cols="70" required ><?= $title ?></textarea>
                <p></p>
            intro: 
                <textarea name="intro" rows="3" cols="70" required><?= $intro ?></textarea>
                <p></p>
            Body:
                <textarea name="body" rows="20" cols="70" required><?= $body ?></textarea>
                <p></p>
            <input type="submit" name="submit"><br>
        </form>
    </body>
</html>