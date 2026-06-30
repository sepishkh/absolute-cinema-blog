<!DOCTYPE html>

<?php

require_once "../config/config.php";
require_once Paths::$UTILZ;

$view_id = $_GET["view"];
if ($view_id == null || empty(trim($view_id))) {
    include Paths::$P404;
    exit();
}
$sqldb = $GLOBALS["Sqldb"];
$stmt = $sqldb->pdo->prepare("SELECT 
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.creation_date,
                                posts.approval,
                                posts.category,
                                posts.hidden,
                                users.fname,
                                users.lname,
                                users.email
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE post_id=:view_id AND posts.hidden IS NULL");

$stmt->execute(
    [":view_id" => $view_id]
);
$content = $stmt->fetch(PDO::FETCH_ASSOC);
if ($content == null) {
    include Paths::$P404;
    exit();
}

$stmt = $sqldb->pdo->prepare("SELECT * 
                                FROM users
                                WHERE email=:email");
$stmt->execute([":email" => GetUsername()]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (
    $content["email"] != GetUsername()
    && $content["approval"] != 1
    && $user["role"] == 0
) {
    include Paths::$P404;
    exit();
}

if (
    $_GET["delete"] === "true" 
    && ($user["role"] > 0 || $content["email"] == GetUsername())) {
    $stmt = $sqldb->pdo->prepare("UPDATE posts
                            SET hidden=1
                            WHERE id=:id");
    $stmt->execute([":id" => $view_id]);
    header("Location: " . Paths::$INDEX);
    exit();
}

if (isset($_GET["approved"]) && $user["role"] > 0) {
    $stmt = $sqldb->pdo->prepare("UPDATE posts
                                    SET approval=:approval
                                    WHERE id=:id");
    $stmt->execute([
        ":approval" => (int)$_GET["approved"],
        ":id" => (int)$view_id
    ]);
}

try {
    $stmt2 = $sqldb->pdo->prepare("SELECT
                                comments.id AS cid,
                                comments.post_id AS pid,
                                comments.author_id AS aid,
                                comments.body,
                                comments.creation_date,
                                comments.approval,
                                users.id AS uid,
                                users.fname,
                                users.lname,
                                users.email
                                FROM comments
                                INNER JOIN users ON uid = aid
                                WHERE (comments.approval=1 OR comments.approval=:control)
                                    AND pid=:post_id
                                ORDER BY comments.creation_date DESC
                            ");
    $stmt2->execute([
        ":post_id" => $view_id,
        ":control" => ($user["role"] > 0) ? 0 : null
    ]);
} catch (PDOException $e) {
    echo $e->getMessage();
    $stmt2 = null;
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo Escape($content["title"]) ?> </title>
    <link rel="stylesheet" href="<?= Paths::$CSS ?>">
</head>

<body>
    <header class="main-header">
        <?php require_once Paths::$HEADER ?>
    </header>

    <main class="content-wrapper article-container">
        <?php if ($user["role"] > 0) : ?>
            <section class="admin-moderation-panel">
                <div class="moderation-meta">
                    <span class="moderation-warning-icon">🛡️</span>
                    <div>
                        <h3>Administrator Controls</h3>
                        <p>This post is currently <strong>Waiting For Approval</strong>. Reviewers cannot read it publicly yet.</p>
                    </div>
                </div>
                <div class="moderation-actions">
                    <a href="<?= Paths::$VIEW . '?view=' . $view_id . '&approved=1' ?>" class="btn-link btn-approve">Approve Post</a>
                    <a href="<?= Paths::$VIEW . '?view=' . $view_id . '&approved=-1' ?>" class="btn-link btn-disapprove">Disapprove</a>
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
            <?php if (IsLoggedIn()) : ?>
                <div class="comment-input-block">
                    <div class="author-avatar-small"><?= substr($user["fname"], 0, 1) ?></div>
                    <form action="<?= Paths::$ROUTE . "?action=comment" ?>" method="POST" class="comment-form">
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
                <h4> Please <a href="<?= Paths::$LOGIN ?>">Login</a> to comment.</h4>
                <br>
            <?php endif ?>
            <div class="comments-list">
                <?php while (($comment = $stmt2->fetch(PDO::FETCH_ASSOC))) : ?>
                    <div class="comment-item">
                        <div class="author-avatar-small"><?= substr($comment["fname"], 0, 1) ?></div>
                        <div class="comment-main-body">
                            <div class="comment-meta">
                                <span class="comment-user"><?= FullName($comment["fname"], $user["lname"]) ?></span>
                                <span class="comment-time"><?= FormatDate($comment["creation_date"]) ?></span>
                            </div>
                            <p class="comment-text-content"><?= $comment["body"] ?></p>
                            <?php if ($user["role"] > 0 && $comment["approval"] == 0) : ?>
                                <form action="<?= Paths::$ROUTE . "?action=appr_cmnt" ?>" method="POST" class="comment-moderation-form">
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
    <footer class="main-footer">
        <?php require_once Paths::$FOOTER ?>
    </footer>
</body>

</html>
