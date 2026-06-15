<?php require_once "utilz.php" ?>

<div class="header-container">
    <div class="header-row logo-row">
        <a href="index.php" class="logo">Absolute<span>Cinema</span></a>
    </div>
    <div class="header-row auth-row">
        <?php if(IsLoggedIn()) : ?>
            <a href="profile.php" class="btn btn-secondary"><?= Escape(GetUsername()) ?></a>
            <a href="index.php?logout=true" class="btn btn-primary">Logout</a>
        <?php else : ?>
            <a href="login.php" class="btn btn-secondary">Log In</a>
            <a href="signup.php" class="btn btn-primary">Sign Up</a>
        <?php endif ?>
    </div>
    <nav class="header-row nav-row">
        <a href="index.php" class="nav-link <?= IsActive("index.php") ?>">Home</a>
        <a href="posts.php" class="nav-link <?= IsActive("posts.php") ?>">All Posts</a>
        <a href="categories.php" class="nav-link <?= IsActive("categories.php") ?>">Categories</a>
    </nav>
</div>