<?php


require_once dirname(__DIR__) . "/config/config.php";

use AbsCin\Models\CommentsModel;

$dbc = $GLOBALS["DBCON"];
$cm = new CommentsModel($dbc);

$appr = $_POST["appr"];
$post_id = $_POST["post_id"];
$cmnt_id = $_POST["cmnt_id"];
$cm->SetApproval($cmnt_id, $appr);
header("Location: " . Paths::$VIEW . "?view=" . $post_id);
exit();
