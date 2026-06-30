<?php

require_once "../config/config.php";

$comment_id = $_POST["comment_id"];
$status = $_POST["status"];
$view_id = $_POST["view_id"];
$sqldb = $GLOBALS["Sqldb"];
$stmt = $sqldb->pdo->prepare("UPDATE comments
                                SET approval=:approval
                                WHERE id=:id");
$stmt->execute([
    ":approval" => (int)$status,
    ":id" => (int)$comment_id
]);
header("Location: " . Paths::$VIEW . "?view=" . $view_id);
exit();
