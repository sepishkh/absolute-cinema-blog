<?php

require_once "../config/config.php";
require_once Paths::$UTILZ;

$sqldb = $GLOBALS["Sqldb"];

$post_id = (int) $_POST["post_id"];
$author_id = (int) $_POST["author_id"];
$cmnt = $_POST["cmnt"];
$stmt = $sqldb->pdo->prepare("INSERT INTO comments
                        (post_id, author_id, body, creation_date, approval)
                        VALUES (:pid, :aid, :body, :creation_date, :approval)");
$stmt->execute([
    ":pid" => $post_id,
    ":aid" => $author_id,
    ":body" => $cmnt,
    ":creation_date" => date("Y-m-d H:i"),
    ":approval" => 0,
]);
header("Location: " . Paths::$VIEW . "?view=" . $post_id);
exit();
