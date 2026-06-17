<!DOCTYPE html>

<!-- TODO: Robust Login/Logout system -->
<!-- TODO: Proper Error Handling -->

<?php

require_once "utilz.php";

if (isset($_GET["logout"])) {
    Logout();
}

require_once "paths.php";
require_once "sqldb.php";
$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

$page_count = 8;
$page_num = 1;
if (isset($_GET["page"])) {
    $page_num = max((int)$_GET["page"], 1);
}
$offset = ($page_num - 1) * $page_count;

$res = $sqldb->pdo->prepare("SELECT
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.creation_date,
                                posts.category,
                                users.fname,
                                users.lname,
                                users.email
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE approval=1 AND hidden IS NULL
                            ORDER BY posts.creation_date DESC
                            LIMIT :limit OFFSET :offset");

$res->execute([
    ":limit" => $page_count,
    ":offset" => $offset
])

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
        <?php require_once "header.php" ?>
        <!-- <img src="/abscin.jpg" alt="Absolute Cinema" class="header-banner"> -->
    </header>

    <main class="content-wrapper">
        <h1 class="page-title">Latest Reviews</h1>
        <div class="reviews-grid">
            <?php while (($post = $res->fetch(PDO::FETCH_ASSOC))) : ?>
                <article class="review-card">
                    <div class="card-thumbnail">
                        <span class="category-badge"><?= GetCategory($post["category"]) ?></span>
                        <div class="thumbnail-placeholder"><?= GetThumbnail($post["category"]) ?></div>
                    </div>

                    <div class="card-details">
                        <h2 class="card-title">
                            <a href="view.php?view=<?= $post["post_id"] ?>"><?= Escape($post["title"]) ?></a>
                        </h2>
                        <p class="card-intro"><?= Escape($post["intro"]) ?></p>
                        <div class="card-meta">
                            <span class="author-name" title="<?= Escape($post["email"]) ?>">
                                <?= FullName($post["fname"], $post["lname"]) ?>
                            </span>
                            <span class="meta-divider">•</span>
                            <time datetime="<?= $post["creation_date"] ?>" class="creation-date"><?= FormatDate($post["creation_date"]) ?></time>
                        </div>
                    </div>
                </article>
            <?php endwhile ?>
        </div>
        <nav class="pagination-container" aria-label="Review Page Navigation">
            <a href="?page=<?= max($page_num - 1, 1) ?>" class="page-nav-btn">
                <span class="btn-arrow">&larr;</span> Previous
            </a>
            <a href="?page=<?= $page_num + 1 ?>" class="page-nav-btn">
                Next <span class="btn-arrow">&rarr;</span>
            </a>
        </nav>
    </main>
    <footer class="main-footer">
        <?php require_once "footer.php" ?>
    </footer>
</body>

</html>