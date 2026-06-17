<?php

require_once "utilz.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    if ($rc[0] == 0) header("Location: view.php?view=" . $rc[1]);
    else header("Location: new.php?status=" . $rc[0]);
    exit();
}
