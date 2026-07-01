<?php

require_once "../config/config.php";
require_once Paths::$UTILZ;

$dbc = $GLOBALS["DBCON"];

$appr = $_POST["appr"];
$post_id = $_POST["post_id"];
$cmnt_id = $_POST["cmnt_id"];
$cmnt_table = CommentTable($post_id);

$stmt = $dbc->pdo->prepare(
    "UPDATE $cmnt_table
    SET approval=:approval
    WHERE id=:id"
);
$stmt->execute([
    ":approval" => (int)$appr,
    ":id" => (int)$cmnt_id
]);
header("Location: " . Paths::$VIEW . "?view=" . $post_id);
exit();