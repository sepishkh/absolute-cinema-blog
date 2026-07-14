<div class="content-wrapper article-container">
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
        <a href="/view?id=<?= $post["id"] ?>&approved=1 ?>" class="btn-link btn-approve">Approve Post</a>
        <a href="/view?id=<?= $post["id"] ?>&approved=-1 ?>" class="btn-link btn-disapprove">Disapprove</a>
    </div>
</section>
<?php endif ?>
<article class="review-full-post">
    <header class="article-header">
        <span class="category-badge-inline"><?= $post["category"] ?> Review</span>
        <h1 class="article-main-title"><?= $post["title"] ?></h1>
        <p class="article-short-intro"><?= $post["intro"] ?></p>
        <div class="article-author-card">
            <div class="author-avatar-small"><?= $author["avatar"] ?></div>
            <div class="author-info">
                <p class="author-fullname"><?= $author["full_name"] ?></p>
                <p class="author-email-link"><a href="mailto:<?= $author["email"] ?>"><?= $author["email"] ?></a></p>
            </div>
            <div class="article-date-meta">
                <time datetime="<?= $post["date"] ?>">Published <?= $post["date_formatted"] ?></time>
            </div>
        </div>
    </header>
    <section class="article-body-content"><?= $post["body"] ?></section>
</article>
<hr class="section-divider">
<section class="comments-section">
    <h2 class="comments-heading">Comments</h2>
    <?php if ($user["logged_in"]) : ?>
    <div class="comment-input-block">
        <div class="author-avatar-small"><?= $user["avatar"] ?></div>
        <form action="<?= Paths::$ROUTE . "?action=comment" ?>" method="POST" class="comment-form">
            <input type="hidden" name="id" value="<?= $post["id"] ?>">
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
    <h4> Please <a href="/login">Login</a> to comment.</h4>
    <br>
    <?php endif ?>
    <div class="comments-list">
        <?= $comments ?>
    </div>
</section>
</div>
