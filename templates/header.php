<?php
    require_once "../config/config.php";
    require_once Paths::$UTILZ;
?>

<div class="header-container">
    <div class="header-row logo-row">
        <a href="<?= Paths::$INDEX ?>" class="logo">Absolute<span>Cinema</span></a>
    </div>
    <div class="header-row auth-row">
        <?php if (IsLoggedIn()) : ?>
            <a href="<?= Paths::$PROFILE ?>" class="btn btn-secondary"><?= Escape(GetUsername()) ?></a>
            <a href="<?= Paths::$INDEX ?>?logout=true" class="btn btn-primary">Logout</a>
        <?php else : ?>
            <a href="<?= Paths::$LOGIN ?>" class="btn btn-secondary">Log In</a>
            <a href="<?= Paths::$SIGNUP ?>" class="btn btn-primary">Sign Up</a>
        <?php endif ?>
    </div>
    <nav class="header-row nav-row">
        <a href="<?= Paths::$INDEX ?>" class="nav-link <?= IsActive("index.php") ?>">Home</a>
        <a href="posts.php" class="nav-link <?= IsActive("posts.php") ?>">All Posts</a>
        <a href="categories.php" class="nav-link <?= IsActive("categories.php") ?>">Categories</a>
    </nav>
</div>