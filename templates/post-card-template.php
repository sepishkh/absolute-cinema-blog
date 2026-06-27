<article class="review-card">
    <div class="card-thumbnail">
        <span class="category-badge"><?= $TEMPLATE_VALUES["CATEGORY"] ?></span>
        <?php if ($TEMPLATE_VALUES["STATUS_BADGE_SW"] === true) : ?>
            <span class="status-badge status-<?= $TEMPLATE_VALUES["STATUS_BADGE_CSS"] ?>"><?= $TEMPLATE_VALUES["STATUS_BADGE"] ?></span>
        <?php endif ?>
        <div class="thumbnail-placeholder"><?= $TEMPLATE_VALUES["THUMBNAIL"] ?></div>
    </div>

    <div class="card-details">
        <h2 class="card-title">
            <a href="<?= Paths::$VIEW . "?view=" . $TEMPLATE_VALUES["ID"] ?>"><?= $TEMPLATE_VALUES["TITLE"] ?></a>
        </h2>
        <?php if ($TEMPLATE_VALUES["INTRO_SW"] === true) : ?>
            <p class="card-intro"><?= $TEMPLATE_VALUES["INTRO"] ?></p>
        <?php endif ?>
        <div class="card-meta">
            <?php if ($TEMPLATE_VALUES["AUTHOR_SW"] === true) : ?>
                <div class="card-meta">
                    <span class="author-name" title="<?= $TEMPLATE_VALUES["EMAIL"] ?>">
                        <?= $TEMPLATE_VALUES["FULL_NAME"] ?>
                    </span>
                    <span class="meta-divider">•</span>
                </div>
            <?php endif ?>
            <time datetime="<?= $TEMPLATE_VALUES["DATE"] ?>" class="creation-date"><?= $TEMPLATE_VALUES["DATE_FORMATTED"] ?></time>
            <?php if ($TEMPLATE_VALUES["POST_ACTIONS_SW"] === true) : ?>
                <div class="post-actions-inline">
                    <a href="<?= Paths::$NEW . "?edit=" . $TEMPLATE_VALUES["ID"] ?>" class="action-link edit-link">Edit</a>
                    <span class="meta-divider">|</span>
                    <a href="<?= Paths::$VIEW . "?view=" . $TEMPLATE_VALUES["ID"] . "&delete=true"?>" class="action-link delete-link" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endif ?>
        </div>
    </div>
</article>