<?php

require_once "../config/config.php";

$view_id = (int) $_POST["view_id"];
$comment = $_POST["comment_text"];
$author_id = (int) $_POST["author_id"];

$sqldb = $GLOBALS["Sqldb"];
$comment_table = "comment_" . $view_id;
$stmt = $sqldb->pdo->prepare("INSERT INTO $comment_table
                        (comment, author_id, approval, creation_date)
                        VALUES (:comment, :author_id, :approval, :creation_date)");
$stmt->execute([
    ":comment" => $comment,
    ":author_id" => $author_id,
    ":approval" => 0,
    ":creation_date" => date("Y-m-d H:i")
]);
header("Location: " . Paths::$VIEW . "?view=" . $view_id);
exit();
