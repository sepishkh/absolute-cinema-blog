<!DOCTYPE html>

<?php

require_once "paths.php";
require_once "sqldb.php";

$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

$stmt = $sqldb->pdo->prepare("SELECT 
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.created_at, 
                                posts.is_updated,
                                users.first_name,
                                users.last_name,
                                users.username,
                                users.avatar_id
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE post_id = :view_id");

if($stmt === false) die("Fucked up");
$res = $stmt->execute(
    array(":view_id" => 1)
);
if($res === false) die("Another fuck up");
$content = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<html>
    <head>
        <title> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <?php require "header.php" ?>
        </header>
        <h1> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </h1>
        <p class="article-author"> <?php echo htmlspecialchars($content['first_name'], ENT_HTML5, "UTF-8") . ' ' . 
                                              htmlspecialchars($content['last_name'], ENT_HTML5, "UTF-8") . " - @" . 
                                              htmlspecialchars($content['username'], ENT_HTML5, "UTF-8") ?></p>
        <p class="article-date"> <?php echo htmlspecialchars($content['created_at'], ENT_HTML5, "UTF-8") ?> </p>
        <p class="article-body"> <?php echo htmlspecialchars($content['intro'], ENT_HTML5, "UTF-8") ?></p>
        <p class="article-cont"> <?php echo $content['body'] ?></p>

    </body>
</html>