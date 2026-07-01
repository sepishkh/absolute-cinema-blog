<!DOCTYPE html>

<!-- TODO: Fix Variable may not be defined warning -->

<?php

require_once "../config/config.php";
require_once Paths::$POSTS_MODEL;
require_once Paths::$USERS_MODEL;
require_once Paths::$UTILZ;

$dbc = $GLOBALS["DBCON"];
$pm = new PostsModel($dbc);
$um = new UsersModel($dbc);

if (IsLoggedIn()) {
    $user = $um->GetUserByEmail(GetUsername())->fetch();
    $appr = $_POST["appr"] ?? 1;
    /* var_dump($user["id"], [$appr]); */
    $posts = $pm->GetPosts(null, [$appr], $user["id"]);
    if ($user["role"] > 0) {
        $appr_admin = $_POST["appr_admin"] ?? 1;
        $panel = $pm->GetPosts(null, [$appr_admin]);
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (IsLoggedIn() ? $user["fname"] . " " : "") ?>Profile</title>
    <link rel="stylesheet" href="<?= Paths::$CSS ?>">
</head>

<body class="profile-page">
    <header class="main-header">
        <?php require_once Paths::$HEADER ?>
    </header>
    <?php if (!IsLoggedIn()) : ?>
        <h1> Please <a href="<?= Paths::$LOGIN ?>">Login</a> first.</h1>
        <?php exit() ?>
    <?php endif ?>

    <main class="content-wrapper">
        <section class="user-dashboard-header">
            <div class="user-profile-meta">
                <div class="profile-avatar-large">
                    <span><?= substr($user["fname"], 0, 1) ?></span>
                </div>
                <div class="user-details">
                    <h1 class="user-name"><?= FullName($user["fname"], $user["lname"]) ?></h1>
                    <p class="user-email"><?= Escape($user["email"]) ?></p>
                    <span class="role-badge role-contributor"><?= GetRole($user["role"]) ?></span>
                </div>
            </div>
            <div class="dashboard-actions">
                <a href="<?= Paths::$NEW ?>" class="btn btn-primary create-post-btn">+ Create New Post</a>
            </div>
        </section>
        <hr class="section-divider">
        <section class="my-posts-section">
            <div class="panel-header-strip">
                <h2 class="page-title">My Posts</h2>
                <form action="<?= Paths::$PROFILE ?>" method="POST" class="profile-filter-form">
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
                <?php while (($post = $posts->fetch())) :
                    $TEMPLATE_VALUES = [
                        "CATEGORY" => GetCategory($post["category"]),
                        "THUMBNAIL" => GetThumbnail($post["category"]),
                        "ID" => $post["id"],
                        "TITLE" => Escape($post["title"]),
                        "INTRO" => Escape($post["intro"]),
                        "EMAIL" => Escape($post["email"]),
                        "FULL_NAME" => FullName($post["fname"], $post["lname"]),
                        "DATE" => $post["creation_date"],
                        "DATE_FORMATTED" => FormatDate($post["creation_date"]),
                        "STATUS_BADGE_SW" => true,
                        "STATUS_BADGE_CSS" => $post["approval"],
                        "STATUS_BADGE" => GetApproval($post["approval"]),
                        "POST_ACTIONS_SW" => true,
                    ];
                    require Paths::$POST_CARD_TEMPLATE;
                endwhile ?>
            </div>
        </section>
        <?php if ($user["role"] > 0) : ?>
            <hr class="section-divider">
            <section class="my-posts-section">
                <div class="panel-header-strip">
                    <h2 class="page-title">Admin Panel</h2>
                    <form action="<?= Paths::$PROFILE ?>" method="POST" class="profile-filter-form">
                        <div class="select-wrapper">
                            <select name="appr_admin" required>
                                <option value="1" <?= ($appr_admin == 1) ? "selected" : "" ?>>Approved</option>
                                <option value="0" <?= ($appr_admin == 0) ? "selected" : "" ?>>Pending</option>
                                <option value="-1" <?= ($appr_admin == -1) ? "selected" : "" ?>>Disapproved</option>
                            </select>
                        </div>
                        <button type="submit" class="profile-filter-btn">Filter</button>
                    </form>
                </div>
                <div class="reviews-grid profile-post-grid">
                    <?php while (($post = $panel->fetch())) :
                        $TEMPLATE_VALUES = [
                            "CATEGORY" => GetCategory($post["category"]),
                            "THUMBNAIL" => GetThumbnail($post["category"]),
                            "ID" => $post["id"],
                            "TITLE" => Escape($post["title"]),
                            "INTRO" => Escape($post["intro"]),
                            "EMAIL" => Escape($post["email"]),
                            "FULL_NAME" => FullName($post["fname"], $post["lname"]),
                            "DATE" => $post["creation_date"],
                            "DATE_FORMATTED" => FormatDate($post["creation_date"]),
                            "STATUS_BADGE_SW" => true,
                            "STATUS_BADGE_CSS" => $post["approval"],
                            "STATUS_BADGE" => GetApproval($post["approval"]),
                            "AUTHOR_SW" => true,
                            "POST_ACTIONS_SW" => true,
                        ];
                        require Paths::$POST_CARD_TEMPLATE;
                    endwhile ?>
                </div>
            </section>
        <?php endif ?>
    </main>
    <footer class="main-footer">
        <?php require_once Paths::$FOOTER ?>
    </footer>
</body>

</html>
