<!DOCTYPE html>

<!-- TODO: Fix Variable may not be defined warning -->

<?php

require_once "utilz.php";

if (IsLoggedIn()) {
    require_once "sqldb.php";
    require_once "paths.php";

    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

    $stmt = $sqldb->pdo->prepare("SELECT * 
                                    FROM users
                                    WHERE email=:email");

    $stmt->execute([":email" => GetUsername()]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $not_found = false;
    if ($user == null) {
        $not_found = true;
    } else {
        $approval = 1;
        if (isset($_POST["approval_select"])) {
            $approval = $_POST["approval_select"];
        }
        $posts = $sqldb->pdo->prepare("SELECT *
                                        FROM posts
                                        WHERE author_id=:id AND approval=:approval
                                        ORDER BY creation_date DESC");
        $posts->execute([
            ":id" => $user["id"],
            ":approval" => $approval
        ]);
        if ($user["role"] > 0) {
            $approval_panel = 1;
            if (isset($_POST["approval_select_panel"])) {
                $approval_panel = $_POST["approval_select_panel"];
            }
            $panel = $sqldb->pdo->prepare("SELECT
                                        posts.id AS post_id,
                                        posts.title, 
                                        posts.intro, 
                                        posts.creation_date,
                                        posts.approval,
                                        posts.category,
                                        users.fname,
                                        users.lname,
                                        users.email
                                        FROM posts
                                        INNER JOIN users ON posts.author_id = users.id
                                        WHERE approval=:approval");
            $panel->execute([":approval" => $approval_panel]);
        }
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (IsLoggedIn() ? $user["fname"] . " " : "") ?>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="profile-page">
    <header class="main-header">
        <?php require_once "header.php" ?>
    </header>
    <?php if (!IsLoggedIn()) : ?>
        <h1> Please <a href="login.php">Login</a> first.</h1>
        <?php exit() ?>
    <?php endif ?>
    <?php if ($not_found) : ?>
        <h1> User not found </h1>
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
                <a href="new.php" class="btn btn-primary create-post-btn">+ Create New Post</a>
            </div>
        </section>
        <hr class="section-divider">
        <section class="my-posts-section">
            <div class="panel-header-strip">
                <h2 class="page-title">My Posts</h2>
                <form action="profile.php" method="POST" class="profile-filter-form">
                    <div class="select-wrapper">
                        <select name="approval_select" required>
                            <option value="1" <?= ($approval == 1) ? "selected" : "" ?>>Approved</option>
                            <option value="0" <?= ($approval == 0) ? "selected" : "" ?>>Pending</option>
                            <option value="-1" <?= ($approval == -1) ? "selected" : "" ?>>Disapproved</option>
                        </select>
                    </div>
                    <button type="submit" class="profile-filter-btn">Filter</button>
                </form>
            </div>
            <div class="reviews-grid profile-post-grid">
                <?php while (($post = $posts->fetch(PDO::FETCH_ASSOC))) : ?>
                    <?php
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
                    require "post-card-template.php";
                    ?>
                <?php endwhile ?>
            </div>
        </section>
        <?php if ($user["role"] > 0) : ?>
            <hr class="section-divider">
            <section class="my-posts-section">
                <div class="panel-header-strip">
                    <h2 class="page-title">Admin Panel</h2>
                    <form action="profile.php" method="POST" class="profile-filter-form">
                        <div class="select-wrapper">
                            <select name="approval_select_panel" required>
                                <option value="1" <?= ($approval_panel == 1) ? "selected" : "" ?>>Approved</option>
                                <option value="0" <?= ($approval_panel == 0) ? "selected" : "" ?>>Pending</option>
                                <option value="-1" <?= ($approval_panel == -1) ? "selected" : "" ?>>Disapproved</option>
                            </select>
                        </div>
                        <button type="submit" class="profile-filter-btn">Filter</button>
                    </form>
                </div>
                <div class="reviews-grid profile-post-grid">
                    <?php while (($post = $panel->fetch(PDO::FETCH_ASSOC))) : ?>
                        <?php
                        $TEMPLATE_VALUES = [
                            "CATEGORY" => GetCategory($post["category"]),
                            "THUMBNAIL" => GetThumbnail($post["category"]),
                            "ID" => $post["post_id"],
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
                        require "post-card-template.php";
                        ?>
                    <?php endwhile ?>
                </div>
            </section>
        <?php endif ?>
    </main>
    <footer class="main-footer">
        <?php require_once "footer.php" ?>
    </footer>
</body>

</html>