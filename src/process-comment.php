<?php

require_once "../config/config.php";

$sqldb = $GLOBALS["Sqldb"];

$post_id = (int) $_POST["post_id"];
$author_id = (int) $_POST["author_id"];
$cmnt = $_POST["cmnt"];
$cmnt_table = CommentTable($post_id);
$stmt = $sqldb->pdo->prepare(
    "INSERT INTO $cmnt_table
    (comment, author_id, approval, creation_date)
    VALUES (:comment, :author_id, :approval, :creation_date)"
);
$stmt->execute([
    ":comment" => $cmnt,
    ":author_id" => $author_id,
    ":approval" => 0,
    ":creation_date" => date("Y-m-d H:i")
]);
header("Location: " . Paths::$VIEW . "?view=" . $post_id);
exit();
