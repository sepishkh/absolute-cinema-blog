<?php require_once "utilz.php" ?>

<h1 class="header-title"><a href="index.php"> Absolute Cinema </h1></a>
<nav>
    <?php if(IsLoggedIn()) : ?>
        <a href="profile.php"> <?php echo htmlspecialchars(GetUsername(), ENT_HTML5, "UTF-8") ?></a> | <a href="index.php?logout=true"> Log Out </a>
    <?php else : ?>
        <a href="login.php">Login</a> | <a href="sign-up.php">Sign up</a>
    <?php endif ?>
</nav>
<nav>
    <a href="index.php">Home</a> | <a href="posts.php">All Posts</a> | <a href="categories">Categories</a>
</nav>