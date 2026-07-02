<!DOCTYPE html>

<?php

require_once "../config/config.php";
require_once Paths::$POSTS_MODEL;
require_once Paths::$COMMENTS_MODEL;
require_once Paths::$UTILZ;

$dbc = $GLOBALS["DBCON"];
$pm = new PostsModel($dbc);
$um = new UsersModel($dbc);
$cm = new CommentsModel($dbc);

$post_id = (int)$_GET["view"];
if (!NotEmpty($post_id)) {
    include Paths::$P404;
    exit();
}

if ($_GET["delete"] === "true") {
    $pm->HidePost($post_id);
    header("Location: " . Paths::$INDEX);
    exit();
}

$posts = $pm->GetPosts($post_id);
$post = $posts->fetch();
if ($post == null) {
    include Paths::$P404;
    exit();
}

$user = $um->GetUserByEmail(GetUsername())->fetch();
if (
    $post["email"] != GetUsername()
    && $post["approval"] != 1
    && $user["role"] == 0
) {
    include Paths::$P404;
    exit();
}

if (
    $_GET["delete"] === "true" 
    && ($user["role"] > 0 || $post["email"] == GetUsername())) {
    $stmt = $sqldb->pdo->prepare("UPDATE posts
                            SET hidden=1
                            WHERE id=:id");
    $stmt->execute([":id" => $post_id]);
    header("Location: " . Paths::$INDEX);
    exit();
}

if (NotEmpty($_GET["approved"]) && $user["role"] > 0) {
    $pm->SetApproval((int)$post_id, (int)$_GET["approved"]);
}

$cmnts = null;
try {
    $cmnts = $cm->GetComments($post_id, ($user["role"] > 0 ? [0, 1] : [1]));
} catch(PDOException $e) {
    echo $e->getMessage();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= Escape($post["title"]) ?> </title>
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
                    <a href="<?= Paths::$VIEW . '?view=' . $post_id . '&approved=1' ?>" class="btn-link btn-approve">Approve Post</a>
                    <a href="<?= Paths::$VIEW . '?view=' . $post_id . '&approved=-1' ?>" class="btn-link btn-disapprove">Disapprove</a>
                </div>
            </section>
        <?php endif ?>
        <article class="review-full-post">
            <header class="article-header">
                <span class="category-badge-inline"><?= GetCategory($post["category"]) ?> Review</span>
                <h1 class="article-main-title"><?= Escape($post["title"]) ?></h1>
                <p class="article-short-intro"><?= $post["intro"] ?></p>
                <div class="article-author-card">
                    <div class="author-avatar-small"><?= substr($post["fname"], 0, 1) ?></div>
                    <div class="author-info">
                        <p class="author-fullname"><?= FullName($post["fname"], $post["lname"]) ?></p>
                        <p class="author-email-link"><a href="mailto:<?= $post["email"] ?>"><?= $post["email"] ?></a></p>
                    </div>
                    <div class="article-date-meta">
                        <time datetime="<?= $post["creation_date"] ?>">Published <?= FormatDate($post["creation_date"]) ?></time>
                    </div>
                </div>
            </header>
            <section class="article-body-content"><?= $post["body"] ?></section>
        </article>
        <hr class="section-divider">
        <section class="comments-section">
            <h2 class="comments-heading">Comments</h2>
            <?php if (IsLoggedIn()) : ?>
                <div class="comment-input-block">
                    <div class="author-avatar-small"><?= substr($user["fname"], 0, 1) ?></div>
                    <form action="<?= Paths::$ROUTE . "?action=comment" ?>" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?= $post_id ?>">
                        <input type="hidden" name="author_id" value="<?= $user["id"] ?>">
                        <div class="form-group">
                            <textarea name="cmnt" rows="3" placeholder="Add a public comment..." required></textarea>
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
                <?php while (($cmnt = $cmnts->fetch())) : ?>
                    <div class="comment-item">
                        <div class="author-avatar-small"><?= substr($cmnt["fname"], 0, 1) ?></div>
                        <div class="comment-main-body">
                            <div class="comment-meta">
                                <span class="comment-user"><?= FullName($cmnt["fname"], $user["lname"]) ?></span>
                                <span class="comment-time"><?= FormatDate($cmnt["creation_date"]) ?></span>
                            </div>
                            <p class="comment-text-content"><?= $cmnt["body"] ?></p>
                            <?php if ($user["role"] > 0 && $cmnt["approval"] == 0) : ?>
                                <form action="<?= Paths::$ROUTE . "?action=appr_cmnt" ?>" method="POST" class="comment-moderation-form">
                                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                    <input type="hidden" name="cmnt_id" value="<?= $cmnt["cid"] ?>">
                                    <button type="submit" name="appr" value="1" class="comment-mod-btn c-approve">
                                        <span class="btn-icon">✔</span> Approve
                                    </button>
                                    <button type="submit" name="appr" value="-1" class="comment-mod-btn c-disapprove">
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
