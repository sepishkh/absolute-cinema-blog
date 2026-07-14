<article class="review-card">
    <div class="card-thumbnail">
        <span class="category-badge"><?= $category ?></span>
        <?php if ($status_badge_sw === true) : ?>
            <span class="status-badge status-<?= $status_badge_css ?>"><?= $status_badge ?></span>
        <?php endif ?>
        <div class="thumbnail-placeholder"><?= $thumbnail ?></div>
    </div>

    <div class="card-details">
        <h2 class="card-title">
            <a href="/view?<?= $view_url ?>"><?= $title ?></a>
        </h2>
        <?php if ($intro_sw === true) : ?>
            <p class="card-intro"><?= $intro ?></p>
        <?php endif ?>
        <div class="card-meta">
            <?php if ($author_sw === true) : ?>
                <div class="card-meta">
                    <span class="author-name" title="<?= $email ?>">
                        <?= $full_name ?>
                    </span>
                    <span class="meta-divider">•</span>
                </div>
            <?php endif ?>
            <time datetime="<?= $date ?>" class="creation-date"><?= $date_formatted ?></time>
            <?php if ($post_actions_sw === true) : ?>
                <div class="post-actions-inline">
                    <a href="<?= Paths::$NEW . "?edit=" . $id ?>" class="action-link edit-link">Edit</a>
                    <span class="meta-divider">|</span>
                    <a href="<?= Paths::$VIEW . "?view=" . $id . "&delete=true"?>" class="action-link delete-link" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endif ?>
        </div>
    </div>
</article>
