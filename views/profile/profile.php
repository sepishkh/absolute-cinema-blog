<div class="content-wrapper">
    <section class="user-dashboard-header">
        <div class="user-profile-meta">
            <div class="profile-avatar-large">
                <span><?= $user["avatar"] ?></span>
            </div>
            <div class="user-details">
                <h1 class="user-name"><?= $user["full_name"] ?></h1>
                <p class="user-email"><?= $user["email"] ?></p>
                <span class="role-badge role-contributor"><?= $user["role"] ?></span>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="/new" class="btn btn-primary create-post-btn">+ Create New Post</a>
        </div>
    </section>
    <hr class="section-divider">
    <?= $my_posts_grid ?>
    <hr class="section-divider">
    <?= $user_posts_grid ?>
</div>
