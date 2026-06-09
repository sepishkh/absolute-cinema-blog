<!DOCTYPE html>

<!-- TODO: Fix intro being required rule -->

<?php

require_once "utilz.php";

if(IsLoggedIn()) {
    $title = $_POST['title'];
    $intro = $_POST['intro'];
    $body = $_POST['body'];
    $submit = $_POST['submit'];
    if ($submit) {
        $ret = NewPost($title, $intro, $body, GetUsername());
    }
}

?>


<html>
    <head>
        <title>New Post</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <?php require_once "header.php" ?>
        </header>
        <?php if(!IsLoggedIn()) : ?>
            <h1> Please <a href="login.php">Login</a> first.</h1>
        <?php exit(); ?>
        <?php endif ?>
        <p></p>
        <form action=<?=htmlspecialchars($_SERVER['PHP_SELF'], ENT_HTML5, "UTF-8")?> method="post">
            Title:
                <textarea name="title" rows="1" cols="70"></textarea>
                <?php if (IsError($title, $submit)) : ?>
                    <span class="error">*Title required</span>
                <?php endif ?>
                <p></p>
            intro: 
                <textarea name="intro" rows="3" cols="70"></textarea>
                <?php if (IsError($intro, $submit)) : ?>
                    <span class="error">*Intro required</span>
                <?php endif ?>
                <p></p>
            Body:
                <textarea name="body" rows="20" cols="70"></textarea>
                <?php if (IsError($body, $submit)) : ?>
                    <span class="error">*Body required</span>
                <?php endif ?>
                <p></p>
            <input type="submit" name="submit"><br>
            <?php if($submit) : ?>
                <?php switch($ret[0]) :
                    case 0: ?>
                        <span class="success">Post successfuly created. <a href="view.php?view=<?=$ret[1]?>">View</a></span>
                <?php   break;
                    default: ?>
                        <span class="error">Erorr creating your post</span>
                <?php endswitch ?>
            <?php endif ?>
        </form>
    </body>
</html>