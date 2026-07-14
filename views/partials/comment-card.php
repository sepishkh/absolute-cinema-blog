<div class="comment-item">
    <div class="author-avatar-small"><?= $avatar ?></div>
    <div class="comment-main-body">
        <div class="comment-meta">
            <span class="comment-user"><?= $full_name ?></span>
            <span class="comment-time"><?= $date_formatted?></span>
        </div>
        <p class="comment-text-content"><?= $body ?></p>
        <?php if ($user_role > 0 && $approval == 0) : ?>
        <form action="<?= Paths::$ROUTE . "?action=appr_cmnt" ?>" method="POST" class="comment-moderation-form">
            <input type="hidden" name="id" value="<?= $post_id ?>">
            <input type="hidden" name="cmnt_id" value="<?= $id ?>">
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
