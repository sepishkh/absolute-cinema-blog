<?php

require_once "../config/config.php";

$view_id = (int) $_POST["view_id"];
$comment = $_POST["comment_text"];
$author_id = (int) $_POST["author_id"];

$sqldb = $GLOBALS["Sqldb"];
$stmt = $sqldb->pdo->prepare("INSERT INTO comments
                        (post_id, author_id, body, creation_date, approval)
                        VALUES (:pid, :aid, :body, :creation_date, :approval)");
$stmt->execute([
    ":pid" => $view_id,
    ":aid" => $author_id,
    ":body" => $comment,
    ":creation_date" => date("Y-m-d H:i"),
    ":approval" => 0,
]);
header("Location: " . Paths::$VIEW . "?view=" . $view_id);
exit();
