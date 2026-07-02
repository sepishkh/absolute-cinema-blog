<?php

require_once "../config/config.php";
require_once Paths::$COMMENTS_MODEL;
require_once Paths::$UTILZ;

$dbc = $GLOBALS["DBCON"];
$cm = new CommentsModel($dbc);

$appr = $_POST["appr"];
$post_id = $_POST["post_id"];
$cmnt_id = $_POST["cmnt_id"];
$cm->SetApproval($cmnt_id, $appr);
header("Location: " . Paths::$VIEW . "?view=" . $post_id);
exit();
