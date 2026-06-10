<?php

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $view_id = (int)$_POST["view_id"];
    $comment = $_POST["comment_text"];
    $author_id = (int)$_POST["author_id"];

    require_once "paths.php";   
    require_once "sqldb.php";   
    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

    $comment_table = "comment_" . $view_id;
    $stmt = $sqldb->pdo->prepare("INSERT INTO {$comment_table}
                            (comment, author_id, approval, created_at)
                            VALUES (:comment, :author_id, :approval, :created_at)");
    $stmt->execute(array(
                            ":comment" => $comment,
                            ":author_id" => $author_id,
                            ":approval" => 0,
                            ":created_at" => date("Y-m-d")              
    ));
}

header("Location: view.php?view=" . $view_id);
exit;