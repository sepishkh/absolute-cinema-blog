<?php


require_once dirname(__DIR__) . "/config/config.php";

use AbsCin\Models\CommentsModel;

$dbc = $GLOBALS["DBCON"];
$cm = new CommentsModel($dbc);

$post_id = (int) $_POST["post_id"];
$author_id = (int) $_POST["author_id"];
$cmnt = $_POST["cmnt"];
$cm->InsertComment($cmnt, $post_id, $author_id, date("Y-m-d H:i"), 0);
header("Location: " . Paths::$VIEW . "?view=" . $post_id);
exit();
