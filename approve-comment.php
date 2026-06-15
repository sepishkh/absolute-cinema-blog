<?php

if($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "paths.php";
    require_once "sqldb.php";
    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

    $comment_id = $_POST["comment_id"];
    $status = $_POST["status"];
    $view_id = $_POST["view_id"];
    $comment_table = "comment_" . $view_id;
    $stmt = $sqldb->pdo->prepare("UPDATE $comment_table
                                    SET approval=:approval
                                    WHERE id=:id");
    $stmt->execute(array(
        ":approval" => (int)$status,
        ":id" => (int)$comment_id
    ));
    header("Location: view.php?view=" . $view_id);
    exit;
}