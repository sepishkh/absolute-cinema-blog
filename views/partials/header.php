<div class="header-container">
    <div class="header-row logo-row">
        <a href="/home" class="logo">Absolute<span>Cinema</span></a>
    </div>
    <div class="header-row auth-row">
        <?php if ($logged_in) : ?>
        <a href="/profile" class="btn btn-secondary"> <?= $username ?> </a>
            <a href="/home?logout=true" class="btn btn-primary">Logout</a>
        <?php else : ?>
            <a href="/login" class="btn btn-secondary">Log In</a>
            <a href="/signup" class="btn btn-primary">Sign Up</a>
        <?php endif ?>
    </div>
    <nav class="header-row nav-row">
        <a href="/home" class="nav-link {{is_active_home}}">Home</a>
        <a href="#" class="nav-link {{is_active_posts}}">All Posts</a>
        <a href="#" class="nav-link {{is_active_categories}}">Categories</a>
    </nav>
</div>
