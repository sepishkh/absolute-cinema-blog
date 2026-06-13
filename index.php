<!DOCTYPE html>

<!-- TODO: Robust Login/Logout system -->
<!-- TODO: Proper Error Handling -->
<!-- TODO: Better UI -->
<!-- TODO: Think about UX -->

<?php

require_once "utilz.php";

if(isset($_GET["logout"])) {
    Logout();
}

require_once "paths.php";
require_once "sqldb.php";
$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

$res = $sqldb->pdo->query("SELECT
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.created_at, 
                                users.first_name,
                                users.last_name,
                                users.email
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE approval=1");

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Absolute Cinema </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <?php require_once 'header.php' ?>
        <!-- <img src="/abscin.jpg" alt="Absolute Cinema" class="header-banner"> -->
    </header>

    <main class="content-wrapper">
        <h1 class="page-title">Latest Reviews</h1>

        <div class="reviews-grid">
            <?php while(($post = $res->fetch(PDO::FETCH_ASSOC))) : ?>
                <article class="review-card">
                    <div class="card-thumbnail">
                        <span class="category-badge">Movie</span>
                        <div class="thumbnail-placeholder">🍿</div>
                    </div>
                    
                    <div class="card-details">
                        <h2 class="card-title">
                            <a href="view.php?view=<?= $post["post_id"] ?>"><?= Escape($post["title"]) ?></a>
                        </h2>
                        <p class="card-intro"><?= Escape($post["intro"]) ?></p>
                        <div class="card-meta">
                            <span class="author-name" title="<?= Escape($post["email"]) ?>">
                                <?= Escape($post["first_name"] . " " . $post["last_name"]) ?>
                            </span>
                            <span class="meta-divider">•</span>
                            <time datetime="<?= $post["created_at"] ?>" class="creation-date"><?= FormatDate($post["created_at"]) ?></time>
                        </div>
                    </div>
                </article>
            <?php endwhile ?>
        </div>

    </main>
</body>
</html>