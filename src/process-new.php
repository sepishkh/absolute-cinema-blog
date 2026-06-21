<?php

require_once "../config/config.php";
require_once Paths::$UTILZ;

$title = $_POST["title"];
$intro = $_POST["intro"];
$body = $_POST["body"];
$category_id = $_POST["category_id"];
$id = isset($_GET["edit"]) ? $_GET["edit"] : null;
if ($id) {
    $rc = UpdatePost($id, $title, $intro, $body, $category_id);
} else {
    $rc = NewPost($title, $intro, $body, GetUsername(), $category_id);
}
if ($rc[0] == 0) header("Location: " . Paths::$VIEW . "?view=" . $rc[1]);
else header("Location: " . Paths::$NEW . "?status=" . $rc[0]);
exit();
