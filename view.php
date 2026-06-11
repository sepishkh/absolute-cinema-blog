<!DOCTYPE html>

<?php

require_once "utilz.php";

$view_id = $_GET['view'];
if($view_id == NULL || empty(trim($view_id))) {
    include "404.php";
    exit;
}

require_once "paths.php";
require_once "sqldb.php";

$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

$stmt = $sqldb->pdo->prepare("SELECT 
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.created_at,
                                posts.approval,
                                users.first_name,
                                users.last_name,
                                users.email
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE post_id = :view_id");

$stmt->execute(
    array(":view_id" => $view_id)
);
$content = $stmt->fetch(PDO::FETCH_ASSOC);
if($content == NULL) {
    include "404.php";
    exit;
}

$stmt = $sqldb->pdo->prepare("SELECT * 
                                FROM users
                                WHERE email=:email");
$stmt->execute(array(":email" => GetUsername()));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($content["email"] != GetUsername() && $content["approval"] != 1 && $user["role"] == 0) {
    include "404.php";
    exit;
}

if(isset($_GET["approved"]) && $user["role"] != 0) {
    $stmt = $sqldb->pdo->prepare("UPDATE posts
                                    SET approval=:approval
                                    WHERE id=:id");
    $stmt->execute(array(":approval" => (int)$_GET["approved"], ":id" => (int)$view_id));
}

$comments_t = "comment_" . $view_id;
try {
    $stmt2 = $sqldb->pdo->prepare("SELECT
                                $comments_t.id AS cid,
                                $comments_t.comment,
                                $comments_t.approval,
                                $comments_t.created_at,
                                $comments_t.author_id,
                                users.id AS uid,
                                users.first_name,
                                users.last_name,
                                users.email
                                FROM $comments_t
                                INNER JOIN users ON uid = $comments_t.author_id
                                WHERE $comments_t.approval=1 OR $comments_t.approval=:control
                            ");
    $stmt2->execute(array(
        ":control" => ($user["role"] > 0) ? 0 : NULL
    ));
} catch(PDOException $e) {
    $stmt2 = NULL;
}

?>

<html>
    <head>
        <title> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <?php require_once "header.php" ?>
        </header>
        <h1> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </h1>
        <?php if($user["role"] > 0) : ?>
            <div class="approves">
                <a href="view.php?view=<?=$view_id?>&approved=1"> <button> Approve </button></a>
                <a href="view.php?view=<?=$view_id?>&approved=-1"> <button> Disapprove </button></a>
            </div>
        <?php endif ?>
        <p class="article-author"> <?php echo htmlspecialchars($content['first_name'], ENT_HTML5, "UTF-8") . " " . 
                                              htmlspecialchars($content['last_name'], ENT_HTML5, "UTF-8") . " - " . 
                                              htmlspecialchars($content['email'], ENT_HTML5, "UTF-8") ?></p>
        <p class="article-date"> <?php echo htmlspecialchars($content['created_at'], ENT_HTML5, "UTF-8") ?> </p>
        <p class="article-body"> <?php echo htmlspecialchars($content['intro'], ENT_HTML5, "UTF-8") ?></p>
        <p class="article-cont"> <?php echo $content['body'] ?></p>   
        <p></p>
        <h3>Comments</h3>
        <?php if(IsLoggedIn()) : ?>
            <div class="comment-input-container">
                <h3>Leave a Comment</h3>
                <form action="submit-comment.php" method="POST" class="comment-form">
                    <input type="hidden" name="view_id" value="<?=$view_id?>">
                    <input type="hidden" name="author_id" value="<?=$user["id"]?>">
                    <div class="form-group">
                        <label Bir="comment_body" class="visually-hidden">Write your comment:</label>
                        <textarea
                            id="comment_body" 
                            name="comment_text" 
                            rows="4" 
                            placeholder="Join the discussion... Add your thoughts here" 
                            required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-submit">Submit Comment</button>
                    </div>
                </form>
            </div>
        <?php else : ?>
            <h4> Please <a href="login.php">Login</a> to comment.</h4>
        <?php endif ?>
        <div class="admin-comments_t-container">
            <?php while(($comment = $stmt2->fetch(PDO::FETCH_ASSOC))) : ?>
                <div class="comment-card">    
                    <div class="comment-avatar">
                        <div class="avatar-circle">U</div>
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author"><?= $comment["first_name"] . " " . $comment["last_name"] ?></span>
                            <span class="comment-date">
                                <?php
                                    $date = DateTime::createFromFormat("Y-m-d", $comment['created_at']);
                                    echo htmlspecialchars($date->format("d M Y"), ENT_HTML5, "UTF-8")
                                ?> 
                            </span>
                        </div>
                        <p class="comment-text"><?= $comment["comment"] ?></p>
                        <?php if($user["role"] > 0 && $comment["approval"] == 0) : ?>
                            <div class="comment-actions">
                                <form action="process-comment.php" method="POST" class="action-form">
                                    <input type="hidden" name="comment_id" value="<?=$comment["cid"]?>">
                                    <input type="hidden" name="status" value="1">
                                    <input type="hidden" name="view_id" value="<?=$view_id?>">
                                    <button type="submit" class="btn btn-approve">Approve</button>
                                </form>
                                <form action="process-comment.php" method="POST" class="action-form">
                                    <input type="hidden" name="comment_id" value="<?=$comment["cid"]?>">
                                    <input type="hidden" name="status" value="-1">
                                    <input type="hidden" name="view_id" value="<?=$view_id?>">
                                    <button type="submit" class="btn btn-disapprove">Disapprove</button>
                                </form>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </body>
</html>