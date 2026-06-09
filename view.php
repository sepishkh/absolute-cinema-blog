<!DOCTYPE html>

<?php

$view_id = $_GET['view'];
if($view_id == NULL || empty(trim($view_id))) {
    include "404.php";
    exit;
}

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
                                users.first_name,
                                users.last_name,
                                users.email,
                                users.avatar_id
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE post_id = :view_id AND approval=1");

$stmt->execute(
    array(":view_id" => $view_id)
);
$content = $stmt->fetch(PDO::FETCH_ASSOC);
if($content == NULL) {
    include "404.php";
    exit;
}

?>

<html>
    <head>
        <title> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <?php require_once "header.php" ?>
        </header>
        <h1> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </h1>
        <p class="article-author"> <?php echo htmlspecialchars($content['first_name'], ENT_HTML5, "UTF-8") . " " . 
                                              htmlspecialchars($content['last_name'], ENT_HTML5, "UTF-8") . " - " . 
                                              htmlspecialchars($content['email'], ENT_HTML5, "UTF-8") ?></p>
        <p class="article-date"> <?php echo htmlspecialchars($content['created_at'], ENT_HTML5, "UTF-8") ?> </p>
        <p class="article-body"> <?php echo htmlspecialchars($content['intro'], ENT_HTML5, "UTF-8") ?></p>
        <p class="article-cont"> <?php echo $content['body'] ?></p>

    </body>
</html>