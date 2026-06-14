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
                                posts.creation_date,
                                posts.approval,
                                posts.category,
                                users.fname,
                                users.lname,
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
                                $comments_t.creation_date,
                                $comments_t.author_id,
                                users.id AS uid,
                                users.fname,
                                users.lname,
                                users.email
                                FROM $comments_t
                                INNER JOIN users ON uid = $comments_t.author_id
                                WHERE $comments_t.approval=1 OR $comments_t.approval=:control
                                ORDER BY $comments_t.creation_date DESC
                            ");
    $stmt2->execute(array(
        ":control" => ($user["role"] > 0) ? 0 : NULL
    ));
} catch(PDOException $e) {
    echo $e->getMessage();
    $stmt2 = NULL;
}

?>

<html lang="en">
    <head><meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> <?php echo htmlspecialchars($content['title'], ENT_HTML5, "UTF-8") ?> </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header class="main-header">
            <?php require_once "header.php" ?>
        </header>

        <main class="content-wrapper article-container">
            <?php if($user["role"] > 0) : ?>
                <section class="admin-moderation-panel">
                    <div class="moderation-meta">
                        <span class="moderation-warning-icon">🛡️</span>
                        <div>
                            <h3>Administrator Controls</h3>
                            <p>This post is currently <strong>Waiting For Approval</strong>. Reviewers cannot read it publicly yet.</p>
                        </div>
                    </div>
                    <div class="moderation-actions">
                        <a href="view.php?view=<?= $view_id ?>&approved=1" class="btn-link btn-approve">Approve Post</a>
                        <a href="view.php?view=<?= $view_id ?>&approved=-1" class="btn-link btn-disapprove">Disapprove</a>
                    </div>
                </section>
            <?php endif ?>
            <article class="review-full-post">
                <header class="article-header">
                    <span class="category-badge-inline"><?= GetCategory($content["category"]) ?> Review</span>
                    <h1 class="article-main-title"><?= Escape($content["title"]) ?></h1>
                    <p class="article-short-intro"><?= $content["intro"] ?></p>
                    <div class="article-author-card">
                        <div class="author-avatar-small"><?= substr($content["fname"], 0, 1) ?></div>
                        <div class="author-info">
                            <p class="author-fullname"><?= FullName($content["fname"], $content["lname"]) ?></p>
                            <p class="author-email-link"><a href="mailto:<?= $content["email"] ?>"><?= $content["email"] ?></a></p>
                        </div>
                        <div class="article-date-meta">
                            <time datetime="<?= $content["creation_date"] ?>">Published <?= FormatDate($content["creation_date"]) ?></time>
                        </div>
                    </div>
                </header>
                <section class="article-body-content"><?= $content["body"] ?></section>
            </article>
            <hr class="section-divider">
            <section class="comments-section">
                <h2 class="comments-heading">Comments</h2>
                <?php if(IsLoggedIn()) : ?>
                    <div class="comment-input-block">
                        <div class="author-avatar-small"><?= substr($user["fname"], 0, 1) ?></div>
                        <form action="submit-comment.php" method="POST" class="comment-form">
                            <input type="hidden" name="view_id" value="<?= $view_id ?>">
                            <input type="hidden" name="author_id" value="<?= $user["id"] ?>">
                            <div class="form-group">
                                <textarea name="comment_text" rows="3" placeholder="Add a public comment..." required></textarea>
                            </div>
                            <div class="comment-submit-row">
                                <button type="submit" class="btn btn-primary btn-comment">Comment</button>
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <h4> Please <a href="login.php">Login</a> to comment.</h4>
                    <br>
                <?php endif ?>
                <div class="comments-list">  
                    <?php while(($comment = $stmt2->fetch(PDO::FETCH_ASSOC))) : ?>
                        <div class="comment-item">
                            <div class="author-avatar-small"><?= substr($comment["fname"], 0, 1) ?></div>
                            <div class="comment-main-body">
                                <div class="comment-meta">
                                    <span class="comment-user"><?= FullName($comment["fname"], $user["lname"]) ?></span>
                                    <span class="comment-time"><?= FormatDate($comment["creation_date"]) ?></span>
                                </div>
                                <p class="comment-text-content"><?= $comment["comment"] ?></p>
                                <?php if($user["role"] > 0 && $comment["approval"] == 0) : ?>
                                    <form action="process-comment.php" method="POST" class="comment-moderation-form">
                                        <input type="hidden" name="view_id" value="<?= $view_id ?>">
                                        <input type="hidden" name="comment_id" value="<?= $comment["cid"] ?>">
                                        <button type="submit" name="status" value="1" class="comment-mod-btn c-approve">
                                            <span class="btn-icon">✔</span> Approve
                                        </button>
                                        <button type="submit" name="status" value="-1" class="comment-mod-btn c-disapprove">
                                            <span class="btn-icon">✖</span> Disapprove
                                        </button>

                                    </form>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endwhile ?>
                </div>
            </section>
        </main>
    </body>
</html>