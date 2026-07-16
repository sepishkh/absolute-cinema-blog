<section class="my-posts-section">
    <div class="panel-header-strip">
        <h2 class="page-title"><?= $title ?></h2>
        <form action="/profile" method="POST" class="profile-filter-form">
            <div class="select-wrapper">
                <select name="appr" required>
                    <option value="1" <?= ($appr == 1) ? "selected" : "" ?>>Approved</option>
                    <option value="0" <?= ($appr == 0) ? "selected" : "" ?>>Pending</option>
                    <option value="-1" <?= ($appr == -1) ? "selected" : "" ?>>Disapproved</option>
                </select>
            </div>
            <button type="submit" class="profile-filter-btn">Filter</button>
        </form>
    </div>
    <div class="reviews-grid profile-post-grid">
        <?= $posts ?>
    </div>
</section>
